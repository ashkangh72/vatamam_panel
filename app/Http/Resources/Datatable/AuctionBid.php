<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Resources\Json\JsonResource;

class AuctionBid extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'is_winner' => $this->is_winner,
            'amount' => $this->amount,
            'created_at' => verta($this->created_at)->format('Y-n-j H:i'),
            'auction' => new Auction($this->auction),
            'user' => $this->user,
        ];
    }
}
