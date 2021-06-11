<?php

namespace App\Exceptions\Auth;

use Exception;

class DockingException extends Exception
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
            // 經銷商IV不存在
            case 'DOCKING_DEALER_IV':
                $data['data'] = ['msg' => __('custom.exception.docking.dealer.iv')];
                $data['code'] = 412;
                break;
            // 經銷商驗證KEY錯誤
            case 'DOCKING_DEALER_KEY':
                $data['data'] = ['msg' => __('custom.exception.docking.dealer.key')];
                $data['code'] = 412;
                break;
            // 對接商會員UID不存在
            case 'DOCKING_USER_UID':
                $data['data'] = ['msg' => __('custom.exception.docking.user.uid')];
                $data['code'] = 404;
                break;
            // 對接商會員UID不存在
            case 'DOCKING_POINT_VERIFY':
                $data['data'] = ['msg' => __('custom.exception.docking.point.verify')];
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
