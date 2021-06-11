<?php
namespace App\Libraries\Binary;

use App\Models\Binary\BinaryCurrencyTrend;
// 例外處理
use App\Exceptions\Libraries\BinaryDrawException;
// 輔助工具
use Carbon\Carbon;

class BinaryDraw
{
    // 走勢ID
    private $trendId = 0;
    // 走勢Model資料
    private $trendModel = null;
    // 二元幣種ID
    private $currencyId = 0;
    // 開獎用基礎資料
    private $baseData = [
        'base' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],   // 自然開獎用基礎號碼
        'max' => [5, 6, 7, 8, 9],                   // "大"基礎號碼
        'min' => [0, 1, 2, 3, 4],                   // "小"基礎號碼
        'odd' => [1, 3, 5, 7, 9],                   // "單"基礎號碼
        'even' => [0, 2, 4, 6, 8],                  // "雙"基礎號碼
    ];
    // 目前投注紀錄
    private $bettingLog = [
        'max' => 0,         // 投注"大"人數
        'min' => 0,         // 投注"小"人數
        'odd' => 0,         // 投注"單"人數
        'even' => 0,        // 投注"雙"人數
        'count' => 0,       // 投注"總"人數
        'threshold' => 100, // 啟動智能開獎人數門檻
    ];
    private $digits = 0;    // 十位數
    private $tens = 0;      // 個位數
    private $draw = 0;      // 開獎(十位數+個位數)
    private $trend = 0.0;   // 走勢(API走勢+開獎)
    private $trendApi = 0;  // API走勢(組合取整數用)
    // 開獎資料
    private $forecastData = [
        // 走勢用
        'startAt' => '00:00:00',     // 開始時間
        'endAt' => '00:00:00',       // 結束時間
        'startT' => '00:00:00',     // 開始時間
        'endT' => '00:00:00',       // 結束時間
        'cycle' => 60,              // 週期秒數
        'range' => 0.08,            // 漲跌範圍
        'rangeMax' => 1,            // 漲跌最大範圍
        'rangeMin' => -1,           // 漲跌最小範圍
    ];
    // 走勢資料
    private $forecast = [
        [
            'name' => '00:00:00',
            'value' => [
                '2021-01-01 00:00:00',
                '0.00',
            ],
        ]
    ];
    // 中獎規則
    private $drawRule = [];
    // 停止投注區塊
    private $area = [];

    /**
     * (自動)建構子
     *
     * @param [object/null] $trendModel     走勢Model資料
     */
    public function __construct($trendModelData=null)
    {
        // 如果有傳正確Model近來可以提前處理
        if(!empty($trendModelData) && get_class($trendModelData) === 'App\Models\Binary\BinaryCurrencyTrend')
        {
            $this->trendModel = $trendModelData;
            // 走勢ID
            $this->trendId = $this->trendModel->id;
            // 幣種ID
            $this->currencyId = $this->trendModel->binary_currency_id;
            // 預處理-設定開獎用基礎資料
            $this->setBaseDara($this->trendModel->rCurrency->trend_data_json);
            // 預處理-設定投注紀錄
            $this->setBetingLog(
                (int) $this->trendModel->max,
                (int) $this->trendModel->min,
                (int) $this->trendModel->odd,
                (int) $this->trendModel->even
            );
            // 預處理-設定走勢
            $this->setTrend($this->trendModel->trend);
        }
    }

    /**
     * (自動)讀取指定走勢資料
     *
     * @param integer $trendId      走勢ID
     */
    public function loadModel(int $trendId)
    {
        // 走勢ID
        $this->trendId = $trendId;
        // 走勢Model資料
        $this->trendModel = BinaryCurrencyTrend::with('rCurrency')->find($trendId);
        if (empty($this->trendModel))
        {
            // 例外處理
            throw new BinaryDrawException('LOADMODEL_NOT_FOUND');
        }
        // 幣種ID
        $this->currencyId = $this->trendModel->binary_currency_id;
        // 預處理-設定開獎用基礎資料
        $this->setBaseDara($this->trendModel->rCurrency->trend_data_json);
        // 預處理-設定投注紀錄
        $this->setBetingLog(
            (int) $this->trendModel->max,
            (int) $this->trendModel->min,
            (int) $this->trendModel->odd,
            (int) $this->trendModel->even
        );
        // 預處理-設定走勢
        $this->setTrend($this->trendModel->trend);
        return $this;
    }

    /**
     * (手動)設定開獎用基礎資料
     * 
     * @輸入 [JSON] $baseJson   一組基礎資料包
     */
    public function setBaseDara($baseJson)
    {
        $this->baseData = json_decode($baseJson, true);
        return $this;
    }

    /**
     * (手動)設定投注紀錄
     *
     * @param integer $max      投注"大"人數
     * @param integer $min      投注"小"人數
     * @param integer $odd      投注"單"人數
     * @param integer $even     投注"雙"人數
     */
    public function setBetingLog(int $max, int $min, int $odd, int $even)
    {
        $this->bettingLog['max'] = $max;
        $this->bettingLog['min'] = $min;
        $this->bettingLog['odd'] = $odd;
        $this->bettingLog['even'] = $even;
        $this->bettingLog['count'] = $max + $min + $odd + $even;
        return $this;
    }

    /**
     * (手動)設定走勢
     *
     * @param int/float $trend      走勢
     */
    public function setTrend($trend)
    {
        $this->trend = $trend;
        return $this;
    }

    /**
     * 設定API取得走勢值
     *
     * @param float $trend      爬取API的即時走勢
     */
    public function setTrendApi(float $trend)
    {
        $this->trendApi = floor($trend);
        return $this;
    }

    public function setTrendCycle($startAt, $endAt, int $cycle, float $range)
    {
        $this->forecastData['startAt'] = $startAt;
        $this->forecastData['endAt'] = $endAt;
        $this->forecastData['cycle'] = $cycle;
        $this->forecastData['range'] = $range;
        // 現在走勢，去換算浮動範圍
        $trend = $this->getTrend();
        $trendRange = $trend * $range;
        $this->forecastData['rangeMax'] = $trendRange;
        $this->forecastData['rangeMin'] = -$trendRange;
        unset($trendRange, $trend);
        return $this;
    }

    /**
     * 運作
     */
    public function run()
    {
        // 1.走勢是不是有值
        if (empty($this->trend))
        {
            // 無
            $this->drawRoute();
        } else {
            // 有
            // 分離既有走勢
            $this->separate();
        }
        return $this;
    }

    // 開獎邏輯
    public function drawRoute()
    {
        // 十位數開獎
        $this->drawDigits();
        if ($this->bettingLog['count'] >= $this->bettingLog['threshold'])
        {
            // 個位數開獎：智能開獎：
            $this->drawTensSmart();
        } else {
            // 個位數開獎：自然開獎：
            $this->drawTensNature();
        }
        $this->draw = sprintf('%s%s', $this->digits, $this->tens);
        $this->trend = sprintf('%s.%s%s', $this->trendApi, $this->digits, $this->tens);
        return $this;
    }

    // 既有走勢反解開獎資料
    public function separate()
    {
        // 切割走勢小數點後拿開獎值
        $exp = explode('.', $this->trend);
        if(count($exp) == 2){
            $draw = array_pop($exp);
            $this->digits = (int) substr($draw, 0, 1);
            $this->tens = (int) substr($draw, 1, 1);
            $this->draw = sprintf('%s%s', $this->digits, $this->tens);
        } else {
            // 開講邏輯
            $this->drawRoute();
        }
        unset($draw, $exp);
        return $this;
    }

    /**
     * 十位數開獎
     */
    public function drawDigits()
    {
        $base = $this->baseData['base'];
        // print_r($base);
        // 隨機十位數
        $this->digits = $base[array_rand($base, 1)];
        unset($base);
        return $this;
    }

    /**
     * 個位數開獎：自然開獎
     */
    public function drawTensNature()
    {
        $base = $this->baseData['base'];
        // print_r($base);
        // 隨機個位數
        $this->tens = $base[array_rand($base, 1)];
        unset($base);
        return $this;
    }

    /**
     * 個位數開獎：智能開獎
     */
    public function drawTensSmart()
    {
        // 開獎
        $quadrant = 1;// 象限
        if($this->bettingLog['max'] > $this->bettingLog['min']){
            // 小
            $quadrant+=2;
        }

        if($this->bettingLog['odd'] > $this->bettingLog['even']){
            // 雙
            $quadrant+=1;
        }
        //   單odd       even雙
        // 大max
        //        579│86
        //          1│2
        // ──────────┼──────────
        //          3│4
        //         13│240
        // 小min
        // print_r($quadrant);
        // 開獎
        switch ($quadrant)
        {
            case 1:
                // 找大&單交集
                $base = array_intersect($this->baseData['max'], $this->baseData['odd']);
                break;
            case 2:
                // 找大&雙交集
                $base = array_intersect($this->baseData['max'], $this->baseData['even']);
                break;
            case 3:
                // 找小&單交集
                $base = array_intersect($this->baseData['min'], $this->baseData['odd']);
                break;
            case 4:
                // 找小&雙交集
                $base = array_intersect($this->baseData['min'], $this->baseData['even']);
                break;
            default:
                $base = $this->baseData['base'];
                // 真隨機開
                break;
        }
        // 個位數
        // print_r($tensBase);
        $this->tens = $base[array_rand($base, 1)];
        unset($base, $quadrant);
        return $this;
    }

    /**
     * 60筆走勢資料產生
     *
     * @return void
     */
    public function forecast()
    {
        $start = Carbon::parse($this->forecastData['startAt']);
        $end = Carbon::parse($this->forecastData['endAt']);
        $point = $this->trend;
        $range = $this->forecastData['range'];
        $this->forecast = [];
        while ($start->lt($end))
        {
            // 點數
            if($start->second != 0)
            {
                // 點數(rand只處理整數，乘100把小數點二位變整數)
                $pointRange = (($point * $range) <= 1.1)? 110 : floor($point * $range * 100);
                $pointRangeEnd = (empty($pointRange))? 110 : floor($pointRange * -1);// 上面有乘了不用再乘100
                $delPoint = rand($pointRange, $pointRangeEnd) / 100;
                // echo 'R：', $pointRange, ' RR：', $pointRangeEnd, "<br/>\n";
                unset($pointRangeEnd, $pointRange);
                // echo 'OOXX：', $point, ' LLL：', $delPoint, "<br/>\n";
                // 除100回復小數點二位
                $point = sprintf('%.2f', $point + $delPoint);
            }
            $this->forecast[] = [
                'name' => $start->format('H:i:s'),
                'value' => [
                    $start->toDateTimeString(),
                    $point,
                ]
            ];
            // echo $start->toDateTimeString() . ' 秒:' . $start->second . "<br>\n";
            // 增加週期秒數
            $start->addSeconds($this->forecastData['cycle']);
            
        }
        unset($range, $point, $end, $start);
        return $this;
    }

    /**
     * 中獎規則產生
     *
     * @return void
     */
    public function drawRule()
    {
        if(in_array($this->tens, $this->baseData['max']))
        {
            $this->drawRule[] = 'max';
        }
        if(in_array($this->tens, $this->baseData['min']))
        {
            $this->drawRule[] = 'min';
        }
        if(in_array($this->tens, $this->baseData['odd']))
        {
            $this->drawRule[] = 'odd';
        }
        if(in_array($this->tens, $this->baseData['even']))
        {
            $this->drawRule[] = 'even';
        }
        return $this;
    }

    /**
     * 取得號碼
     */
    public function get()
    {
        return $this->draw;
    }

    /**
     * 取得開獎走勢值
     */
    public function getTrend()
    {
        return $this->trend;
    }

    /**
     * 取走勢
     */
    public function getForecast()
    {
        return json_encode($this->forecast);
    }

    /**
     * 取中獎規則
     */
    public function getDrawRule()
    {
        return json_encode($this->drawRule);
    }

    /**
     * 建構子
     */
    public function __destruct()
    {
    }
}
