<?php

namespace App\Http\Resources\Api\AttributeGroup;

use Illuminate\Http\{Request, Resources\Json\JsonResource};

class AttributeGroupResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'type'       => $this->type,
            'ordering'   => $this->ordering,
        ];
    }
}
