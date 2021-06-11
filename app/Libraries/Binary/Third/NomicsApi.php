<?php
namespace App\Libraries\Binary\Third;

use Illuminate\Support\Facades\Http;

class NomicsApi
{
    private $ssl;
    private $domain;
    private $api = [
        'currencies' => 'v1/currencies',
        'ticker' => 'v1/currencies/ticker',
        'sparkline' => 'v1/currencies/sparkline',
    ];

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->ssl = env('NOMICS_API_SSL');
        $this->domain = env('NOMICS_API_DOMAIN');
    }

    /**
     * 取得數位幣介紹
     *
     * @param integer $page
     * @param integer $row
     * @return string
     */
    public function getCurrencies($page = 1, $row = 50)
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['currencies']);
        $formData = [
            'key' => env('NOMICS_API_KEY'),
            'ids' => 'AUD,BTC,CNH,DASH,EOS,ETH,EUR,GPB,LTC',
            'attributes' => 'original_symbol,name,description,website_url,logo_url',
            'format' => 'json',
            'per-page' => $row,
            'page' => $page,
        ];
        $response = Http::get($apiUrl, $formData);
        $jsonData = $response->body();
        return $jsonData;
    }

    /**
     * 取得數位幣股市代碼
     *
     * @param integer $page
     * @param integer $row
     * @return string
     */
    public function getTicker($ids, $convert, $page = 1, $row = 50)
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['ticker']);
        $formData = [
            'key' => env('NOMICS_API_KEY'),
            'ids' => 'AUD,BTC,CNH,DASH,EOS,ETH,EUR,GPB,LTC',
            'interval' => '1h',
            'convert' => 'USD',
            'per-page' => $row,
            'page' => $page,
        ];
        $response = Http::get($apiUrl, $formData);
        $jsonData = $response->body();
        return $jsonData;
    }

    /**
     * 取得數位幣迷你圖表資料
     *
     * @param integer $page
     * @param integer $row
     * @return string
     */
    public function getSparkline($start, $end)
    {
        $apiUrl = sprintf('%s://%s/%s', $this->ssl, $this->domain, $this->api['sparkline']);
        $formData = [
            'key' => env('NOMICS_API_KEY'),
            'ids' => 'AUD,BTC,CNH,DASH,EOS,ETH,EUR,GPB,LTC',
            'start' => $start,
            // 'start' => '2021-03-17T17:00:00Z',
            'end' => $end,
            // 'end' => '2021-03-18T17:00:00Z',
        ];
        $response = Http::get($apiUrl, $formData);
        $jsonData = $response->body();
        return $jsonData;
    }

    /**
     * 建構子
     */
    public function __destruct()
    {
    }
}
