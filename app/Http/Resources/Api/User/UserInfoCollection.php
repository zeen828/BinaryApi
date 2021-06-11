<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\ResourceCollection;
// 預設格式
use App\Http\Resources\Custom\DefaultResources;
// 單筆格式
use App\Http\Resources\Api\User\UserInfoResource as Collection;

class UserInfoCollection extends ResourceCollection
{
    use DefaultResources;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = Collection::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'data' => $this->collection,
        ];
    }
}
