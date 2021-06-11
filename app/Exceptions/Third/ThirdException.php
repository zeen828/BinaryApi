<?php

namespace App\Exceptions\Third;

use Exception;

class ThirdException extends Exception
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
            case 'THIRD_AUTH_CLIENT':
                $data['data'] = ['msg' => __('custom.exception.third.auth.client')];
                $data['code'] = 404;
                break;
            case 'THIRD_AUTH_USER':
                $data['data'] = ['msg' => __('custom.exception.third.auth.user')];
                $data['code'] = 404;
                break;
            case 'THIRD_AUTH_SIGNATURE':
                $data['data'] = ['msg' => __('custom.exception.third.auth.signature')];
                $data['code'] = 404;
                break;
            case 'THIRD_AUTH_USER_SIGNATURE':
                $data['data'] = ['msg' => __('custom.exception.third.auth.userSignature')];
                $data['code'] = 404;
                break;
            case 'THIRD_READ_CLIENT':
                $data['data'] = ['msg' => __('custom.exception.third.read.client')];
                $data['code'] = 404;
                break;
            case 'THIRD_READ_USER':
                $data['data'] = ['msg' => __('custom.exception.third.read.user')];
                $data['code'] = 404;
                break;
            case 'THIRD_AUTH':
                $data['data'] = ['msg' => __('custom.exception.third.auth.notFound')];
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
