<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'id' => $this->id,
            'name' => htmlspecialchars($this->name),
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'national_id' => $this->national_id,
            'money' => $this->getWallet()->balance,
            'box' => is_null($this->safeBox) ? 0 : $this->safeBox->balance,
            'created_at' => tverta($this->created_at)->format('%d %B %Y'),

            'links' => [
                'unblock' => route('admin.users.unblock', ['user' => $this]),
                'edit' => route('admin.users.edit', ['user' => $this]),
                'show' => route('admin.users.show', ['user' => $this]),
                'details' => route('admin.mali.detail.index', [
                    'fullname' => '',
                    'username' => '',
                    'phone' => '',
                    'id' => $this->id
                ]),
            ]
        ];
    }
}
