<?php
namespace App\Libraries\Docking;

// 外部查詢
use Illuminate\Support\Facades\Http;
// 例外處理
use App\Exceptions\Third\ThirdException;

class DockingAuth
{
    private $ssl;
    private $domain;
    private $api = [
        'authClient' => 'api/v1/auth/token',
        'authUser' => 'api/v1/auth/user/login/member',
        'authSignature' => 'business/binary/profile',
        'signatureUser' => 'api/v1/auth/user/signature/login',
        'readClient' => 'api/v1/auth/service',
        'readUser' => 'api/v1/member/auth/me',
        'thirdSignature' => 'api/v1/business/auth/link',
        'thirdInvite' => 'api/v1/business/invite/link',
        'thirdInviteSignature' => 'api/v1/business/invite/auth',
        'businessAuth' => 'business/bet/profile',
    ];
    private $client_id;
    private $client_secret;
    private $client_token;
    private $client;
    private $user_token;
    private $user;
    private $user_uid;
    private $inviter_uid;
    private $debug = false;

    public function __construct()
    {
        $this->ssl = env('DOCKING_API_SSL');
        $this->domain = env('DOCKING_API_DOMAIN');
        // $this->domain = 'demo02.jbgbet.com';
        $this->client_id = env('DOCKING_API_CLIENT_ID');
        // $this->client_id = '63cb7f2feea546eb8bed792214e18d9b';
        $this->client_secret = env('DOCKING_API_CLIENT_SECRET');
        // $this->client_secret = '806d5264b1ee33d54023a7f96bc44613';
    }

    // 除錯用
    public function debug()
    {
        $this->debug = true;
    }

    // Client授權
    public function authClient()
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['authClient']);
        $formData = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
        $response = Http::asForm()->post($apiUrl, $formData);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_AUTH_CLIENT');
        }
        $jsonData = $response->json();
        $this->client_token = $jsonData['data']['access_token'];
        return $this;
    }

    // User帳號授權
    public function authUser($account, $password)
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['authUser']);
        $formData = [
            'account' => $account,
            'password' => $password,
        ];
        $response = Http::withToken($this->client_token)->asForm()->post($apiUrl, $formData);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_AUTH_USER');
        }
        $jsonData = $response->json();
        $this->user_token = $jsonData['data']['access_token'];
        return $this;
    }

    // 簽章授權
    public function authSignature($signature)
    {
        $apiUrl = sprintf('%s://%s/%s/%s', $this->ssl, $this->domain, $this->api['authSignature'], $signature);
        $formData = [
            'signature' => $signature,
        ];
        $response = Http::asForm()->get($apiUrl);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_AUTH_SIGNATURE');
        }
        $jsonData = $response->json();
        $this->user_uid = $jsonData['data']['uid'];
        $this->inviter_uid = $jsonData['data']['inviter_uid'];
        return $this;
    }

    // User簽章授權
    public function authUserSignature($signature)
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['signatureUser']);
        $formData = [
            'signature' => $signature,
        ];
        $response = Http::withToken($this->client_token)->asForm()->post($apiUrl, $formData);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_AUTH_USER_SIGNATURE');
        }
        $jsonData = $response->json();
        $this->user_token = $jsonData['data']['access_token'];
        return $this;
    }

    // Client資料
    public function readClient()
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['readClient']);
        $formData = [];
        $response = Http::withToken($this->client_token)->get($apiUrl);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_READ_CLIENT');
        }
        $jsonData = $response->json();
        $this->client = $jsonData['data'];
        return $this;
    }

    // User資料
    public function readUser()
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['readUser']);
        $formData = [];
        $response = Http::withToken($this->user_token)->get($apiUrl);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_READ_USER');
        }
        $jsonData = $response->json();
        $this->user = $jsonData['data'];
        return $this;
    }

    // 簽章登入
    public function thirdAuth($signature)
    {
        $apiUrl = sprintf('%s://%s/%s/%s', $this->ssl, $this->domain, $this->api['businessAuth'], $signature);
        $formData = [];
        $response = Http::withToken($this->user_token)->get($apiUrl);
        if ($response->failed()) {
            if ($this->debug === true) {
                var_dump($apiUrl);
                var_dump($formData);
                var_dump($response->body());
                exit();
            }
            // 錯誤處理
            throw new ThirdException('THIRD_AUTH');
        }
        $jsonData = $response->json();
        // $this->user = $jsonData['data'];
        $this->user_uid = $jsonData['data']['uid'];
        $this->inviter_uid = $jsonData['data']['inviter_uid'];
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getClientToken()
    {
        return $this->client_token;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserToken()
    {
        return $this->user_token;
    }

    // 會員平台UID
    public function getUserUid()
    {
        return $this->user_uid;
    }

    // 邀請者UID
    public function getUseRinviterUid()
    {
        return $this->inviter_uid;
    }
}
