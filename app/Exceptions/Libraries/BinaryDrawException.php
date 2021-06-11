<?php

namespace App\Exceptions\Libraries;

use Exception;

class BinaryDrawException extends Exception
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
            case 'LOADMODEL_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.libraries.binaryDraw.loadmodel.notFound')];
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
