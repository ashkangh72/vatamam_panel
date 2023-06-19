<?php

namespace App\Http\Resources\Datatable\Faq;

use Illuminate\Http\{
    Request,
    Resources\Json\ResourceCollection
};

class FaqCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'page'      => $this->currentPage(),
                'pages'     => $this->lastPage(),
                'perpage'   => $this->perPage(),
                'rowIds'    => $this->collection->pluck('id')->toArray()
            ],
        ];
    }
}
