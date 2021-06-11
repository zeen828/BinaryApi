<?php

namespace App\Http\Resources\Api\Binary;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class BinaryResource extends JsonResource
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
            'binary_id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'logo' => $this->logo,
            'description' => $this->description_zh,
            'website' => $this->website,
            'sort' => $this->sort,
        ];
    }
}
