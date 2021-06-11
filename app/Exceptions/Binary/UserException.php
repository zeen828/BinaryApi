<?php

namespace App\Exceptions\Binary;

use Exception;

class UserException extends Exception
{
    public function report()
    {
    }

    public function render($request, $exception)
    {
        $data = [
            'data' => [
                'msg' => __('custom.exception.test'),
            ],
            'success' => false,
            'code' => 400,
        ];
        switch ($exception->getMessage())
        {
            // 呼叫自訂例外給定的名稱
            // 使用者不存在
            case 'USER_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.user.notFound')];
                $data['code'] = 404;
                break;
            case 'USER_POINT_NOT_ENOUGH':
                $data['data'] = ['msg' => __('custom.exception.user.point.notEnough')];
                $data['code'] = 404;
                break;
            default:
                // $data['data'] = ['msg' => __('custom.exception.test')];
                // $data['code'] = 400;
                break;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
