<?php

namespace App\Http\Resources\Api\Specification;

use Illuminate\Http\{Request, Resources\Json\ResourceCollection};
use Illuminate\Support\Collection;

class SpecificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        return $this->collection->map(function ($specification) {
            return [
                'id'        => $specification->id,
                'name'      => $specification->name,
                'value'     => $specification->pivot->value,
            ];
        });
    }
}
