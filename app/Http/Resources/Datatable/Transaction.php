<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'transaction_id'    => $this->id,
            'name'              => $this->user ? htmlspecialchars($this->user->name) : '-',
            'created_at'        => tverta($this->created_at)->format('%d %B %Y'),
            'amount'            => number_format($this->amount) . ' تومان',
            'status'            => $this->status,

            'links' => [
                'view'    => route('admin.transactions.show', ['transaction' => $this]),
                'destroy' => route('admin.transactions.destroy', ['transaction' => $this]),
                'user'    => $this->user ? route('admin.users.show', ['user' => $this->user]) : '#',
            ]
        ];
    }
}
