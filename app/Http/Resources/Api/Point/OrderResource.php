<?php

namespace App\Http\Resources\Api\Point;

use Illuminate\Http\Resources\Json\JsonResource;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;

class OrderResource extends JsonResource
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
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'point' => (float) $this->point,
            'order_id' => $this->order_id,
            'order_sn' => $this->order_sn,
            // 'order_order_sn' => $this->order_order_sn,
            'order_event' => $this->order_event,
            'order_point' => (float) $this->order_point,
            'order_remarks' => $this->order_remarks,
        ];
    }
}
