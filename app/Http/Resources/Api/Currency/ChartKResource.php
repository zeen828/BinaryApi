<?php

namespace App\Http\Resources\Api\Currency;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class ChartKResource extends JsonResource
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
            'date' => $this->date,
            'open' => $this->open,
            'close' => $this->close,
            'high' => $this->high,
            'low' => $this->low,
        ];
    }
}
