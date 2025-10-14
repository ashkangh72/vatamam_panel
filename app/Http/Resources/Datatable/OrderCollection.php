<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'page' => $this->currentPage(),
                'pages' => $this->lastPage(),
                'perpage' => $this->perPage(),
                'rowIds' => $this->collection->pluck('id')->toArray()
            ],
        ];
    }
}
