<?php

namespace App\Http\Resources\Api\Jwt;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class TokenResource extends JsonResource
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
        return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        // ];
    }
}
