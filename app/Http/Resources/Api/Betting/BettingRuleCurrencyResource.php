<?php

namespace App\Http\Resources\Api\Betting;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class BettingRuleCurrencyResource extends JsonResource
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
        $option = [];
        $betArr = json_decode($this->bet_json, true);
        foreach($betArr as $key => $val)
        {
            $option[] = [
                'rule_currency_id' => $this->id,
                'value' => $key,
                'title' => $val,
            ];
        }
        return [
            'rule_currency_id' => $this->id,
            // 'binary' => $this->rCurrency->binary_name,
            // 'currency' => $this->rCurrency->currency_name,
            'name' => $this->name,
            'option' => $option,
            'sort' => (float) $this->sort,
            'odds' => $this->odds,
        ];
    }
}
