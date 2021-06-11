<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class RecordBettingResource extends JsonResource
{
    use DefaultResources;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'binary' => $this->rCurrency->binary_name,
            'currency' => $this->rCurrency->currency_name,
            'value' => $this->binary_rule_currency_value,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'profit' => $this->profit,
            'win_user' => $this->win_user,
        ];
    }
}
