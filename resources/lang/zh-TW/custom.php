<?php

return [
    'exception' => [
        'test' => '測試',
        'libraries' => [
            'binaryDraw' => [
                'loadmodel' => [
                    'notFound' => '讀取Model不存在!',
                ],
            ],
        ],
        'third' => [
            'auth' => [
                'notFound' => '第三方平台對接受權失敗！',
                'client' => '第三方平台對接客戶端錯誤！',
                'user' => '第三方平台對接用戶錯誤！',
                'signature' => '第三方平台對接簽章錯誤！',
                'userSignature' => '第三方平台對接用戶簽章錯誤！',
            ],
            'read' => [
                'client' => '第三方平台讀取客戶端錯誤！',
                'user' => '第三方平台讀取用戶錯誤！',
            ],
        ],
        'jwt' => [
            'token' => [
                'notFound' => 'Token不存在！',
                'invalid' => 'Token無效！',
                'expired' => 'Token過期！',
            ],
        ],
        'docking' => [
            'dealer' => [
                'iv' => '經銷商IV不存在！',
                'key' => '經銷商驗證Key錯誤！',
            ],
            'user' => [
                'uid' => '對接會員Uid不存在！',
            ],
            'point' => [
                'verify' =>'點數驗證錯誤！',
            ]
        ],
        'user' => [
            'notFound' => '使用者不存在！',
            'point' => [
                'notEnough' => '使用者點數不足！',
            ],
        ],
        'binary' => [
            'currency' => [
                'notFound' => '二元幣種不存在！',
                'trend' => [
                    'notFound' => '二元幣種走勢期數不存在！',
                ],
            ],
            'rule' => [
                'type' => [
                    'notFound' => '二元規則類型不存在！',
                ],
                'currency' => [
                    'notFound' => '二元幣種規則不存在！',
                ],
            ],
        ],
        'betting' => '',
    ],
];
