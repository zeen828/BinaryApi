<?php

namespace App\Exceptions\Auth;

use Exception;

class JwtException extends Exception
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
            // JWT token 不存在
            case 'JWT_TOKEN_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.jwt.token.notFound')];
                $data['code'] = 401;
                break;
            // JWT token 無效
            case 'JWT_TOKEN_INVALID':
                $data['data'] = ['msg' => __('custom.exception.jwt.token.invalid')];
                $data['code'] = 403;
                break;
            // JWT token 過期
            case 'JWT_TOKEN_EXPIRED':
                $data['data'] = ['msg' => __('custom.exception.jwt.token.expired')];
                $data['code'] = 403;
                break;
            default:
                // $data['data'] = ['msg' => __('custom.exception.test')];
                // $data['code'] = 400;
                break;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
