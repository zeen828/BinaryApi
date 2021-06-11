<?php

namespace App\Exceptions\Binary;

use Exception;

class BettingException extends Exception
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
            // 二元幣種不存在
            case 'USER_POINT_NOT_ENOUGH':
                $data['data'] = ['msg' => __('custom.exception.user.point.notEnough')];
                $data['code'] = 412;
                break;
            default:
                // $data['data'] = ['msg' => __('custom.exception.test')];
                // $data['code'] = 400;
                break;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
