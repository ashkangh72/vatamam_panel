<?php

namespace App\Http\Resources\Api\Price;

use App\Http\Resources\Api\Attribute\AttributeCollection;
use Illuminate\Http\{Request, Resources\Json\JsonResource};

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'price'            => $this->discountPrice(),
            'regular_price'    => $this->tomanPrice(),
            'sale_price'       => $this->discountPrice(),
            'discount'         => $this->discount,
            'discount_price'   => $this->discount_price,
            'cart_max'         => $this->cart_max ? min($this->cart_max, $this->stock) : $this->stock,
            'cart_min'         => $this->cart_min,
            'attributes'       => new AttributeCollection($this->get_attributes)
        ];
    }
}
