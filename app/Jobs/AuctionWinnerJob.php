<?php

namespace App\Jobs;

use App\Models\Auction;
use Carbon\Carbon;
use App\Enums\{OrderStatusEnum, AuctionBidTypeEnum};
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
        $winnerBidExists = $auction->bids()->where('is_winner', true)->exists();
        $winnerBid = $auction->bids()
            ->where('type', AuctionBidTypeEnum::bid)
            ->orderBy('amount', 'desc')
            ->first();

        if ($auction->is_ended || $winnerBidExists) return;

        if (!$winnerBid) {
            $auction->update([
                'is_ended' => true,
            ]);

            return;
        }

        if ($auction->minimum_sale_price > $winnerBid->amount) {
            $auction->update([
                'is_ended' => true,
            ]);

            return;
        }

        $winner = $winnerBid->user;
        $order = $winner->orders()
            ->where('seller_id', $auction->user_id)
            ->where('status', OrderStatusEnum::pending)
            ->first();

        if (!$order) {
            $order = $winner->order()->create([
                'seller_id' => $auction->user_id,
                'quantity' => 1,
                'price' => $winnerBid->amount,
                'discount_price' => $winnerBid->amount,
                'discount_amount' => 0,
                'shipping_cost' => $auction->shipping_cost,
            ]);

            dispatch(new CancelOrderJob($order, $auction, $winnerBid))->delay(Carbon::parse($order->created_at)->addHours(12));
        } else {
            $order->update([
                'price' => $order->price + $winnerBid->amount,
                'discount_price' => $order->discount_price + $winnerBid->amount,
            ]);
        }

        DB::table('order_auction')->insert([
            'order_id' => $order->id,
            'auction_id' => $auction->id,
            'price' => $winnerBid->amount,
        ]);

        $winnerBid->update(['is_winner' => true]);

        $auction->update(['is_ended' => true]);
    }
}
