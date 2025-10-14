<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\{AuctionLoser, Order, Auction};
use App\Enums\{OrderStatusEnum, AuctionBidTypeEnum, SafeBoxHistoryTypeEnum, WalletHistoryTypeEnum};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CancelOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Order $order;
    private Auction $auction;
    private $winnerBid;
    private int $skip;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, Auction $auction, $winnerBid, int $skip = 1)
    {
        $this->order = $order;
        $this->auction = $auction;
        $this->winnerBid = $winnerBid;
        $this->skip = $skip;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->order->status == OrderStatusEnum::paid &&
            $this->order->auctions()->where('auction_id', $this->auction->id)->first()->status == 'paid') {
            return;
        }

        AuctionLoser::create(['auction_bid_id' => $this->winnerBid->id]);
        $this->order->update(['status' => OrderStatusEnum::canceled]);
        $this->winnerBid->update(['is_winner' => false]);

        if ($this->auction->guaranteed) $this->handleLoserAuctionGuarantee($this->winnerBid->user);

        $winnerBid = $this->auction->bids()
            ->where('type', AuctionBidTypeEnum::bid)
            ->orderBy('amount', 'desc')
            ->skip($this->skip)
            ->first();

        if (!$winnerBid || $this->auction->minimum_sale_price > $winnerBid->amount) {
            if ($this->auction->guaranteed) $this->refundUsersGuarantee();

            return;
        }

        $winner = $winnerBid->user;

        $order = $winner->orders()
            ->where('seller_id', $this->auction->user_id)
            ->where('status', '!=', OrderStatusEnum::canceled)
            ->whereIn('shipping_status', [null, 'pending'])
            ->whereNull('is_satisfied')
            ->first();

        if (!$order) {
            $order = $winner->order()->create([
                'seller_id' => $this->auction->user_id,
                'quantity' => 1,
                'price' => $winnerBid->amount,
                'discount_price' => $winnerBid->amount,
                'discount_amount' => 0,
                'shipping_cost' => $this->auction->shipping_cost,
            ]);

            dispatch(new CancelOrderJob($order, $this->auction, $winnerBid, $this->skip + 1))->delay(Carbon::parse($order->created_at)->addHours(48));
        } else {
            $order->update([
                'price' => $order->price + $winnerBid->amount,
                'discount_price' => $order->discount_price + $winnerBid->amount,
                'status' => OrderStatusEnum::locked
            ]);
        }

        DB::table('order_auction')->insert([
            'order_id' => $order->id,
            'auction_id' => $this->auction->id,
            'price' => $winnerBid->amount,
        ]);

        $winnerBid->update([
            'is_winner' => true,
        ]);

        $winner->sendWinningAuctionNotification($this->auction);
    }

    private function handleLoserAuctionGuarantee($user)
    {
        $safeBox = $user->safeBox;

        $paidGuarantee = $this->auction->safeBoxHistory()
            ->where('safe_box_id', $safeBox->id)
            ->where('type', SafeBoxHistoryTypeEnum::auction_guarantee)
            ->success()
            ->first();

        if (!$paidGuarantee) return;

        $safeBox->histories()->create([
            'type' => SafeBoxHistoryTypeEnum::checkout,
            'amount' => $paidGuarantee->amount,
            'balance' => $safeBox->balance() - $paidGuarantee->amount,
            'description' => 'برداشت مبلغ تضمین پرداخت شده در مزایده ' . $this->auction->title . ' و واریز به حساب پارکینگ پروانه به عنوان جریمه و جبران خسارت',
            'success' => true,
            'historiable_type' => Auction::class,
            'historiable_id' => $this->id,
        ]);
        $safeBox->refreshBalance();
    }

    private function refundUsersGuarantee()
    {
        $auctionPaidGuarantees = $this->auction->safeBoxHistory()
            ->where('type', SafeBoxHistoryTypeEnum::auction_guarantee)
            ->success()
            ->get();

        foreach ($auctionPaidGuarantees as $auctionPaidGuarantee) {
            $payer = $auctionPaidGuarantee->safeBox->user;
            $payerSafeBox = $payer->safeBox;
            $payerWallet = $payer->wallet;
            $alreadyRefunded = $payerSafeBox->histories()
                ->where('type', SafeBoxHistoryTypeEnum::checkout)
                ->where('historiable_type', Auction::class)
                ->where('historiable_id', $this->auction->id)
                ->where('success', true)
                ->exists();

            if ($alreadyRefunded) continue;

            $payerSafeBox->histories()->create([
                'type' => SafeBoxHistoryTypeEnum::checkout,
                'amount' => $auctionPaidGuarantee->amount,
                'balance' => $auctionPaidGuarantee->safeBox->balance() - $auctionPaidGuarantee->amount,
                'description' => 'استرداد مبلغ تضمین پرداخت شده در مزایده ' . $this->auction->title . ' و واریز به کیف پول',
                'success' => true,
                'historiable_type' => Auction::class,
                'historiable_id' => $this->auction->id,
            ]);
            $payerSafeBox->refreshBalance();

            $payerWallet->histories()->create([
                'type' => WalletHistoryTypeEnum::refund,
                'amount' => $auctionPaidGuarantee->amount,
                'balance' => $payerWallet->balance() + $auctionPaidGuarantee->amount,
                'description' => 'استرداد مبلغ تضمین پرداخت شده در مزایده ' . $this->auction->title . ' از صندوق امانت',
                'success' => true,
                'historiable_type' => Auction::class,
                'historiable_id' => $this->auction->id,
            ]);
            $payerWallet->refreshBalance();
        }
    }
}
