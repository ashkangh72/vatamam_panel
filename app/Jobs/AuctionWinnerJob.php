<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\{Auction, Order};
use App\Enums\{OrderStatusEnum, AuctionBidTypeEnum, SafeBoxHistoryTypeEnum, WalletHistoryTypeEnum};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AuctionWinnerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $auctionId;
    public $auction;
    
    /**
     * Create a new job instance.
     */
    public function __construct(int $auctionId)
    {
        $this->auctionId = $auctionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $auction = Auction::find($this->auctionId);
        $this->auction = $auction;
        $winnerBidExists = $auction->bids()->where('is_winner', true)->exists();
        $winnerBid = $auction->bids()
            ->where('type', AuctionBidTypeEnum::bid)
            ->orderBy('amount', 'desc')
            ->first();

        if ($auction->is_ended || $winnerBidExists) return;

        if (Carbon::parse($auction->end_at)->isFuture()) return;

        $auction->user->sendAuctionEndNotification($auction);

        if (!$winnerBid || $auction->minimum_sale_price > $winnerBid->amount) {
            $auction->update([
                'is_ended' => true,
            ]);

            if ($this->auction->guaranteed) $this->refundUsersGuarantee();

            return;
        }

        $winner = $winnerBid->user;
        $order = $winner->orders()
            ->where('seller_id', $auction->user_id)
            ->where('status', '!=', OrderStatusEnum::canceled)
            ->whereIn('shipping_status', [null, 'pending'])
            ->whereNull('is_satisfied')
            ->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => $winner->id,
                'seller_id' => $auction->user_id,
                'quantity' => 1,
                'status' => OrderStatusEnum::locked,
                'price' => $winnerBid->amount,
                'discount_price' => $winnerBid->amount,
                'discount_amount' => 0,
                'shipping_cost' => $auction->shipping_cost,
            ]);

            dispatch(new CancelOrderJob($order, $auction, $winnerBid))->delay(Carbon::parse($order->created_at)->addHours(48));
        } else {
            $order->update([
                'price' => $order->price + $winnerBid->amount,
                'discount_price' => $order->discount_price + $winnerBid->amount
            ]);
        }

        DB::table('order_auction')->insert([
            'order_id' => $order->id,
            'auction_id' => $auction->id,
            'price' => $winnerBid->amount,
        ]);

        $winnerBid->update(['is_winner' => true]);

        $auction->update(['is_ended' => true]);

        $winner->sendWinningAuctionNotification($auction);
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
