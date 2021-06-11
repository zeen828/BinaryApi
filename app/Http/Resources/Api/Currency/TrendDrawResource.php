<?php

namespace App\Http\Resources\Api\Currency;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrendDrawResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'logo' => $this->logo,
        ];
    }
}
