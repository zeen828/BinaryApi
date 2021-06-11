<?php

namespace App\Exceptions\Binary;

use Exception;

class BinaryException extends Exception
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
            case 'BINARY_CURRENCY_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.binary.currency.notFound')];
                $data['code'] = 412;
                break;
            // 二元幣種走勢期數不存在
            case 'BINARY_CURRENCY_TREND_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.binary.currency.trend.notFound')];
                $data['code'] = 412;
                break;
            // 二元規則類型不存在
            case 'BINARY_RULE_TYPE_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.binary.rule.type.notFound')];
                $data['code'] = 412;
                break;
            // 二元幣種規則不存在
            case 'BINARY_RULE_CURRENCY_NOT_FOUND':
                $data['data'] = ['msg' => __('custom.exception.binary.rule.currency.notFound')];
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
