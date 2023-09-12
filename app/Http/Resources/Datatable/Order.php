<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $today = todayVerta();
        $createdAt = tverta($this->created_at);

        if ($today->format('Y-n-j') == $createdAt->format('Y-n-j')) {
            $formattedCreatedAt = $createdAt->formatDifference($today);
        } else {
            $formattedCreatedAt = $createdAt->format('%d %B %Y');
        }

        return [
            'id' => $this->id,
            'order_id' => $this->id,
            'user_profile' => route('admin.users.show', ['user' => $this->user_id]),
            'seller_profile' => route('admin.users.show', ['user' => $this->seller_id]),
            'user' => [
                'name' => $this->user->name,
            ],
            'seller' => [
                'name' => $this->seller->name,
            ],
            'created_at' => $formattedCreatedAt,
            'price' => number_format($this->price) . ' تومان',
            'status' => $this->status->name,
            'shipping_status' => $this->shipping_status,
            'is_refunded' => $this->isRefunded(),

            'links' => [
                'view' => route('admin.orders.show', ['order' => $this]),
            ]
        ];
    }
}
