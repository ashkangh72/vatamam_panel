<?php

namespace App\Http\Resources\Datatable\Faq;

use Illuminate\Http\{
    Request,
    Resources\Json\JsonResource
};

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'ordering'          => $this->ordering,
            'question'          => $this->question,
            'answer'            => $this->answer,

            'links' => [
                'edit'    => route('admin.faqs.edit', ['faq' => $this]),
            ]
        ];
    }
}
