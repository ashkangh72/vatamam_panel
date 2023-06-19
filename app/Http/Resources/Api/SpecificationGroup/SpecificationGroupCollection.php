<?php

namespace App\Http\Resources\Api\SpecificationGroup;

use App\Http\Resources\Api\Specification\SpecificationCollection;
use Illuminate\Http\{Request, Resources\Json\ResourceCollection};
use Illuminate\Support\Collection;

class SpecificationGroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        $product = ($this->additional)['product'];

        return $this->collection->map(function ($group) use ($product) {
            $specifications = $product->specifications()->where('specification_group_id', $group->id)->get()->unique();

            return [
                'id'             => $group->id,
                'name'           => $group->name,
                'specifications' => new SpecificationCollection($specifications)
            ];
        });
    }
}
