<?php

namespace App\Http\Resources\Custom;

/**
 * 預設回傳主體格式
 */
trait DefaultResources
{
    /**
     * 更動回傳資料格式(第一層)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true,
            'code' => 200,
        ];
    }
}
