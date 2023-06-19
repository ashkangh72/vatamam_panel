<?php

namespace App\Http\Resources\Api\Gallery;

use Illuminate\Http\{Request, Resources\Json\ResourceCollection};
use Illuminate\Support\Collection;

class GalleryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        return $this->collection->map(function ($gallery) {
            return [
                'id'        => $gallery->id,
                'image'     => asset($gallery->image),
                'ordering'  => $gallery->ordering,
            ];
        });
    }
}
