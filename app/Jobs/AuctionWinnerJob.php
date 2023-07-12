<?php

namespace App\Jobs;

use App\Enums\OrderStatusEnum;
use App\Models\Auction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        $winnerBid = $auction->bids()->orderBy('amount', 'desc')->first();
        $winner = $winnerBid->user;
        $order = $winner->orders()
            ->where('seller_id', $auction->user_id)
            ->where('status', OrderStatusEnum::pending)
            ->first();

        if (!$order) {
            $order = $winner->order()->create([
                'seller_id' => $auction->user_id,
                'name' => $winner->name,
                'quantity' => 1,
                'shipping_cost' => 0,
                'price' => $winnerBid->amount,
                'is_satisfied' => false,
            ]);
        }

        DB::table('order_auction')->insert([
            'order_id' => $order->id,
            'auction_id' => $auction->id,
            'price' => $winnerBid->amount,
            'title' => $auction->title,
            'quantity' => 1,
            'status' => 'pending',
        ]);

        $winnerBid->update([
            'is_winner' => true,
        ]);

        $auction->update([
            'is_ended' => true,
        ]);
    }
}
