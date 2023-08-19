<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Enums\{OrderStatusEnum, AuctionBidTypeEnum};
use App\Models\{AuctionBid, AuctionLoser, Order, Auction};
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

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, Auction $auction, $winnerBid)
    {
        $this->order = $order;
        $this->auction = $auction;
        $this->winnerBid = $winnerBid;
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

        AuctionLoser::create(['auction_bid_id', $this->winnerBid->id]);
        $this->order->update(['status' => OrderStatusEnum::canceled]);
        $this->winnerBid->update(['is_winner' => false]);

        $winnerBid = $this->auction->bids()
            ->where('type', AuctionBidTypeEnum::bid)
            ->orderBy('amount', 'desc')
            ->skip(1)
            ->first();

        if (!$winnerBid || $this->auction->minimum_sale_price > $winnerBid->amount) return;

        $winner = $winnerBid->user;
        $order = $winner->orders()
            ->where('seller_id', $this->auction->user_id)
            ->where('status', OrderStatusEnum::pending)
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

            dispatch(new CancelOrderJob($order, $this->auction, $winnerBid))->delay(Carbon::parse($order->created_at)->addHours(12));
        } else {
            $order->update([
                'price' => $order->price + $winnerBid->amount,
                'discount_price' => $order->discount_price + $winnerBid->amount,
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
    }
}
