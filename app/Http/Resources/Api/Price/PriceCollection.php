<?php

namespace App\Http\Resources\Api\Price;

use Illuminate\Http\{Request, Resources\Json\ResourceCollection};

class PriceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
