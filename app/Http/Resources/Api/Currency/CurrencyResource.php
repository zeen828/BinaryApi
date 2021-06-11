<?php

namespace App\Http\Resources\Api\Currency;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class CurrencyResource extends JsonResource
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
            'binary_id' => $this->binary_id,
            'currency_id' => $this->id,
            'binary' => $this->binary_name,
            'binary_code' => $this->binary_code,
            'currency' => $this->currency_name,
            'currency_code' => $this->currency_code,
            'description' => $this->rBinary->description_zh,
            'logo' => $this->rBinary->logo,
            'period' => $this->period,
            'draw_at' => $this->draw_at,
            'trend' => $this->trend,
        ];
    }
}
