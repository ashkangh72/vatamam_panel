<?php

namespace App\Http\Resources\Api\Attribute;

use App\Http\Resources\Api\AttributeGroup\AttributeGroupResource;
use Illuminate\Http\{Request, Resources\Json\ResourceCollection};
use Illuminate\Support\Collection;

class AttributeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        return $this->collection->map(function ($attribute) {
            return [
                'id'        => $attribute->id,
                'name'      => $attribute->name,
                'value'     => $attribute->value,
                'group'     => new AttributeGroupResource($attribute->group)
            ];
        });
    }
}
