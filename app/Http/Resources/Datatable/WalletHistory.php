<?php

namespace App\Http\Resources\Datatable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletHistory extends JsonResource
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
            'name'              => $this->wallet->user ? htmlspecialchars($this->wallet->user->name) : '-',
            'created_at'        => tverta($this->created_at)->format('%d %B %Y'),
            'amount'            => number_format($this->amount) . ' تومان',
            'type'            => $this->getType(),
            'description'            => $this->description,

        ];
    }

    public function getType() {
        if($this->type->value == 1){
            return 'واریز وجه';
        }else if($this->type->value == 2){
            return 'برداشت وجه';
        }else if($this->type->value == 3){
            return 'واریز وجه توسط ادمین';
        }else if($this->type->value == 4){
            return 'برداشت وجه توسط ادمین';
        }else if($this->type->value == 5){
            return 'درآمد';
        }else if($this->type->value == 6){
            return 'بازگشت وجه';
        }else if($this->type->value == 7){
            return 'کمیسیون';
        }
    }
}
