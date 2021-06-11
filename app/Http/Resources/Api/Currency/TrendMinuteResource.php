<?php

namespace App\Http\Resources\Api\Currency;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class TrendMinuteResource extends JsonResource
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
            'trend_id' => $this->id,
            'binary_currency_id' => $this->binary_currency_id,
            'period' => $this->period,
            'draw_at' => $this->draw_at,
            'trend' => $this->trend,
        ];
    }
}
