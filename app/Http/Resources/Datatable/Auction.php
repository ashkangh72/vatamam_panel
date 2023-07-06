<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Resources\Json\JsonResource;

class Auction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'                => $this->id,
            'image'             => $this->imageUrl,
            'title'             => htmlspecialchars($this->title),
            'sku'               => htmlspecialchars($this->sku),
            'base_price'        => number_format($this->base_price),
            'status'            => $this->status->name,
            'username'          => $this->user->name,
            'category'          => $this->category->title,
            'created_at'        => tverta($this->created_at)->format('%d %B %Y'),

            'links' => [
                'accept'    => route('admin.auctions.accept', ['auction' => $this]),
                'reject'    => route('admin.auctions.reject', ['auction' => $this]),
            ]
        ];
    }
}
