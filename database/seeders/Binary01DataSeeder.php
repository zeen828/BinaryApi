<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Binary\BinaryCurrencyTrend;
// 時間
use Carbon\Carbon;

class Binary01DataSeeder extends Seeder
{
    public function __forecast($draw = '100.00', $hour = '00', $minute = '00')
    {
        // 變數
        $forecast = array(
            'trend' => [],
            'markArea' => [],
        );
        // 公式
        $formula = array(
            123.12,
            456.45,
            789.78,
            -147.14,
            -258.25,
            -369.36,
        );
        // 暫存上一筆值
        $tmpDraw = 0;
        // 
        $day = date('Y-m-d');
        for ($i=0;$i<60;$i++) {
            // $time = sprintf('%s%02d', date('h:i:'), $i);
            $time = sprintf('%s:%s:%02d', $hour, $minute, $i);
            // $forecast['title'][] = $time;
            if ($i == 0) {
                // 第一筆開獎結果
                $tmpDraw = $draw;
            } else {
                // 隨機一筆公式
                $key = array_rand($formula, 1);
                $tmpDraw = sprintf('%.2f',(float)$tmpDraw + (float)$formula[$key]);
            }
            // $forecast['val'][] = $tmpDraw;
            $forecast['trend'][] = [
                'name' => $time,
                'value' => [
                    sprintf('%s %s', $day, $time),
                    $tmpDraw,
                ],
            ];
            if ($i == 50) $forecast['markArea']['start'] = $time;
            if ($i == 59) $forecast['markArea']['end'] = $time;
        }

        return json_encode($forecast);
        // return $forecast;
    }

    /**
     * Run the database seeds.
     * 
     * 指令:: php artisan db:seed --class=Binary01DataSeeder
     * 
     * @return void
     */
    public function run()
    {
        // $tmp = $this->__forecast('5462.75', '01', '07');
        // print_r($tmp);
        // exit();

        $now_date = date('Y-m-d h:i:s');

        // 網站配置
        DB::table('website')->truncate();
        DB::table('website')->insert([
            [
                'id' => '1',
                'title' => '二元期權',
                'description' => '二元期權遊戲',
                'keyword' => '[]',
                'ga_key' => 'GA123456789',
                'domain' => '127.0.0.1',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
        ]);

        // 經銷商
        DB::table('website_dealer')->truncate();
        DB::table('website_dealer')->insert([
            [
                'iv' => 'platform',
                'name' => '平台使用',
                'key' => 'qaz123wsx456edc789rfv',
                'ip' => '127.0.0.1',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'iv' => 'jasn',
                'name' => '佳鑫使用',
                'key' => 'tHk5qz6Qbpv3yDrB',
                'ip' => '127.0.0.1',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
        ]);

        // [測試資料]會員-投注紀錄
        DB::table('user_betting')->truncate();
        DB::table('user_betting')->insert([
            // 買大買小
            [
                'id' => '1',
                'user_id' => '1',                       // will
                'binary_currency_id' => '1',            // BTC > USD
                'binary_currency_trend_id' => '2',      // TEST202103301600
                'binary_rule_currency_id' => '1',       // 買大買小
                'binary_rule_currency_value' => 'max',  // 大 / 46
                'quantity' => '1',                      // 1注
                'amount' => '10',                       // 10元
                'profit' => '9.6',                      // 9.6元
                'win_sys' => '1',                       // 贏
                'win_user' => '0',                      // 贏
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '2',
                'user_id' => '1',                       // will
                'binary_currency_id' => '1',            // BTC > USD
                'binary_currency_trend_id' => '2',      // TEST202103301600
                'binary_rule_currency_id' => '1',       // 買大買小
                'binary_rule_currency_value' => 'min',  // 小 / 46
                'quantity' => '1',                      // 1注
                'amount' => '10',                       // 10元
                'profit' => '9.6',                      // 9.6元
                'win_sys' => '0',                       // 輸
                'win_user' => '0',                      // 輸
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            // 買單買雙
            [
                'id' => '3',
                'user_id' => '1',                       // will
                'binary_currency_id' => '1',            // BTC > USD
                'binary_currency_trend_id' => '2',      // TEST202103301600
                'binary_rule_currency_id' => '2',       // 買單買雙
                'binary_rule_currency_value' => 'odd',  // 單 / 46
                'quantity' => '1',                      // 1注
                'amount' => '10',                       // 10元
                'profit' => '9.6',                      // 9.6元
                'win_sys' => '0',                       // 輸
                'win_user' => '0',                      // 輸
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '4',
                'user_id' => '1',                       // will
                'binary_currency_id' => '1',            // BTC > USD
                'binary_currency_trend_id' => '2',      // TEST202103301600
                'binary_rule_currency_id' => '2',       // 買單買雙
                'binary_rule_currency_value' => 'even', // 雙 / 46
                'quantity' => '1',                      // 1注
                'amount' => '10',                       // 10元
                'profit' => '9.6',                      // 9.6元
                'win_sys' => '1',                       // 贏
                'win_user' => '0',                      // 贏
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            // 買漲買跌
            [
                'id' => '5',
                'user_id' => '1',                       // will
                'binary_currency_id' => '1',            // BTC > USD
                'binary_currency_trend_id' => '2',      // TEST202103301600
                'binary_rule_currency_id' => '3',       // 買漲買跌
                'binary_rule_currency_value' => 'rise', // 漲 / 5436.81 > 5576.46
                'quantity' => '1',                      // 1注
                'amount' => '10',                       // 10元
                'profit' => '9.6',                      // 9.6元
                'win_sys' => '1',                       // 贏
                'win_user' => '0',                      // 贏
                // 狀態
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '6',
                'user_id' => '1',                       // will
                'binary_currency_id' => '1',            // BTC > USD
                'binary_currency_trend_id' => '2',      // TEST202103301600
                'binary_rule_currency_id' => '3',       // 買漲買跌
                'binary_rule_currency_value' => 'fall', // 跌 / 5436.81 > 5576.46
                'quantity' => '1',                      // 1注
                'amount' => '10',                       // 10元
                'profit' => '9.6',                      // 9.6元
                'win_sys' => '0',                       // 輸
                'win_user' => '0',                      // 輸
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
        ]);

        // [測試資料]會員-投注紀錄
        DB::table('order')->truncate();
        DB::table('order')->insert([
            [
                'id' => '1',
                'sn' => 'BBB202104141048D1',
                'user_id' => '1',
                'order_sn' => 'DOCKING202104141048D1',
                'event' => 'deposit',
                'point' => '1000',
                'remarks' => '轉入',
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '2',
                'sn' => 'BBB202104141048D2',
                'user_id' => '2',
                'order_sn' => 'DOCKING202104141048D2',
                'event' => 'deposit',
                'point' => '1000',
                'remarks' => '轉入',
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '3',
                'sn' => 'BBB202104141048D3',
                'user_id' => '1',
                'order_sn' => 'DOCKING202104141048D3',
                'event' => 'payment',
                'point' => '500',
                'remarks' => '轉出',
                // 狀態
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
        ]);

        // 二元期權
        DB::table('binary')->truncate();
        DB::table('binary')->insert([
            [
                'id' => '1',
                'name' => 'Bitcoin',
                'code' => 'BTC',
                'logo' => 'https://s3.us-east-2.amazonaws.com/nomics-api/static/images/currencies/btc.svg',
                'description_zh' => '"比特幣"是一種基於去中心化，採用點對點網路與共識主動性，開放原始碼，以區塊鏈作為底層技術的加密貨幣，比特幣由中本聰於2008年10月31日發表論文，2009年1月3日，創世區塊誕生。在某些國家、央行、政府機關則將比特幣視為虛擬商品，而不認為是貨幣。',
                'website' => 'https://bitcoin.org/en/',
                'currency' => 'USD',
                'sort' => '1',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '2',
                'name' => 'Dash',
                'code' => 'DASH',
                'logo' => 'https://s3.us-east-2.amazonaws.com/nomics-api/static/images/currencies/dash.svg',
                'description_zh' => '"達世幣"是一個專注於支付行業的開源對等網絡加密貨幣。達世幣以比特幣為藍本，關注於易用性和可擴展性。',
                'website' => 'https://www.dash.org/',
                'currency' => 'USD',
                'sort' => '2',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '3',
                'name' => 'EOS',
                'code' => 'EOS',
                'logo' => 'https://s3.us-east-2.amazonaws.com/nomics-api/static/images/currencies/eos.svg',
                'description_zh' => 'EOS Description zh',
                'website' => 'https://eos.io/',
                'currency' => 'USD',
                'sort' => '3',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '4',
                'name' => 'Ethereum',
                'code' => 'ETH',
                'logo' => 'https://s3.us-east-2.amazonaws.com/nomics-api/static/images/currencies/eth.svg',
                'description_zh' => '以太坊區塊鏈上的代幣稱為"以太幣"，代碼為ETH，可在許多加密貨幣的外匯市場上交易，它也是以太坊上用來支付交易手續費和運算服務的媒介。',
                'website' => 'https://www.ethereum.org/',
                'currency' => 'USD',
                'sort' => '4',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '5',
                'name' => 'Litecoin',
                'code' => 'LTC',
                'logo' => 'https://s3.us-east-2.amazonaws.com/nomics-api/static/images/currencies/ltc.svg',
                'description_zh' => '"萊特幣"是一種點對點的電子加密貨幣，也是MIT/X11許可下的一個開源軟體項目。',
                'website' => 'https://litecoin.org/',
                'currency' => 'USD',
                'sort' => '5',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ]
        ]);

        // 二元期權-幣種
        DB::table('binary_currency')->truncate();
        DB::table('binary_currency')->insert([
            [
                'id' => '1',
                'binary_id' => '1',
                'binary_name' => 'Bitcoin',
                'binary_code' => 'BTC',
                'currency_name' => 'USD',
                'currency_code' => 'USD',
                'trend_data_json' => '{"base":["0","1","2","3","4","5","6","7","8","9"],"max":["5","6","7","8","9"],"min":["0","1","2","3","4"],"odd":["1","3","5","7","9"],"even":["0","2","4","6","8"]}',
                'trend_digits' => '1',
                'trend_repeat' => '0',
                'forecast_data_json' => '["1","2","3","4","5","6","7","8","9","0"]',
                'forecast_digits' => '59',
                'forecast_repeat' => '1',
                'week' => '["1","2","3","4","5","6","7"]',
                'start_t' => '09:00:00',
                'end_t' => '20:29:59',
                'stop_enter' => '10',
                'repeat' => '60',
                'reservation' => '1',
                'win_rate' => '0.40',
                'sort' => '1',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '2',
                'binary_id' => '2',
                'binary_name' => 'Dash',
                'binary_code' => 'DASH',
                'currency_name' => 'USD',
                'currency_code' => 'USD',
                'trend_data_json' => '{"base":["0","1","2","3","4","5","6","7","8","9"],"max":["5","6","7","8","9"],"min":["0","1","2","3","4"],"odd":["1","3","5","7","9"],"even":["0","2","4","6","8"]}',
                'trend_digits' => '1',
                'trend_repeat' => '0',
                'forecast_data_json' => '["1","2","3","4","5","6","7","8","9","0"]',
                'forecast_digits' => '59',
                'forecast_repeat' => '1',
                'week' => '["1","2","3","4","5","6","7"]',
                'start_t' => '10:00:00',
                'end_t' => '14:59:59',
                'stop_enter' => '10',
                'repeat' => '60',
                'reservation' => '1',
                'win_rate' => '0.40',
                'sort' => '2',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '3',
                'binary_id' => '3',
                'binary_name' => 'EOS',
                'binary_code' => 'EOS',
                'currency_name' => 'USD',
                'currency_code' => 'USD',
                'trend_data_json' => '{"base":["0","1","2","3","4","5","6","7","8","9"],"max":["5","6","7","8","9"],"min":["0","1","2","3","4"],"odd":["1","3","5","7","9"],"even":["0","2","4","6","8"]}',
                'trend_digits' => '1',
                'trend_repeat' => '0',
                'forecast_data_json' => '["1","2","3","4","5","6","7","8","9","0"]',
                'forecast_digits' => '59',
                'forecast_repeat' => '1',
                'week' => '["1","2","3","4","5","6","7"]',
                'start_t' => '12:00:00',
                'end_t' => '17:59:59',
                'stop_enter' => '10',
                'repeat' => '60',
                'reservation' => '1',
                'win_rate' => '0.40',
                'sort' => '3',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '4',
                'binary_id' => '4',
                'binary_name' => 'Ethereum',
                'binary_code' => 'ETH',
                'currency_name' => 'USD',
                'currency_code' => 'USD',
                'trend_data_json' => '{"base":["0","1","2","3","4","5","6","7","8","9"],"max":["5","6","7","8","9"],"min":["0","1","2","3","4"],"odd":["1","3","5","7","9"],"even":["0","2","4","6","8"]}',
                'trend_digits' => '1',
                'trend_repeat' => '0',
                'forecast_data_json' => '["1","2","3","4","5","6","7","8","9","0"]',
                'forecast_digits' => '59',
                'forecast_repeat' => '1',
                'week' => '["1","2","3","4","5","6","7"]',
                'start_t' => '13:00:00',
                'end_t' => '19:59:59',
                'stop_enter' => '10',
                'repeat' => '60',
                'reservation' => '1',
                'win_rate' => '0.40',
                'sort' => '4',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '5',
                'binary_id' => '5',
                'binary_name' => 'Litecoin',
                'binary_code' => 'LTC',
                'currency_name' => 'USD',
                'currency_code' => 'USD',
                'trend_data_json' => '{"base":["0","1","2","3","4","5","6","7","8","9"],"max":["5","6","7","8","9"],"min":["0","1","2","3","4"],"odd":["1","3","5","7","9"],"even":["0","2","4","6","8"]}',
                'trend_digits' => '1',
                'trend_repeat' => '0',
                'forecast_data_json' => '["1","2","3","4","5","6","7","8","9","0"]',
                'forecast_digits' => '59',
                'forecast_repeat' => '1',
                'week' => '["1","2","3","4","5","6","7"]',
                'start_t' => '11:00:00',
                'end_t' => '16:59:59',
                'stop_enter' => '10',
                'repeat' => '60',
                'reservation' => '1',
                'win_rate' => '0.40',
                'sort' => '5',
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ]
        ]);

        // 二元期權-規則-類型
        DB::table('binary_rule_type')->truncate();
        DB::table('binary_rule_type')->insert([
            [
                'id' => '1',
                'name' => '大小',// 名稱
                'description' => '已小數點第二位數為準，舉例走勢57387.18取小數點第二位為8，01234為小56789為大，此例為買大贏',// 描述
                'sort' => '1',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '2',
                'name' => '單雙',// 名稱
                'description' => '已小數點第二位數為準，舉例走勢57387.18取小數點第二位為8，13579為單02468為雙，此例為買雙贏',// 描述
                'sort' => '2',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '3',
                'name' => '漲跌',// 名稱
                'description' => '',// 描述
                'sort' => '3',// 排序
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
        ]);

        // 二元期權-規則-類型
        DB::table('binary_rule_currency')->truncate();
        DB::table('binary_rule_currency')->insert([
            [
                'id' => '1',
                'binary_currency_id' => '1',// 期權-幣種id
                'binary_rule_type_id' => '1',// 二元期權-規則-類型id
                'name' => '大小',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"max":"大","min":"小"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '1',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '2',
                'binary_currency_id' => '1',// 期權-幣種id
                'binary_rule_type_id' => '2',// 二元期權-規則-類型id
                'name' => '單雙',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"odd":"單","even":"雙"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '2',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '3',
                'binary_currency_id' => '1',// 期權-幣種id
                'binary_rule_type_id' => '3',// 二元期權-規則-類型id
                'name' => '漲跌',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"rise":"漲","fall":"跌"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '3',// 排序
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '4',
                'binary_currency_id' => '2',// 期權-幣種id
                'binary_rule_type_id' => '1',// 二元期權-規則-類型id
                'name' => '大小',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"max":"大","min":"小"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '1',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '5',
                'binary_currency_id' => '2',// 期權-幣種id
                'binary_rule_type_id' => '2',// 二元期權-規則-類型id
                'name' => '單雙',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"odd":"單","even":"雙"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '2',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '6',
                'binary_currency_id' => '2',// 期權-幣種id
                'binary_rule_type_id' => '3',// 二元期權-規則-類型id
                'name' => '漲跌',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"rise":"漲","fall":"跌"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '3',// 排序
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '7',
                'binary_currency_id' => '3',// 期權-幣種id
                'binary_rule_type_id' => '1',// 二元期權-規則-類型id
                'name' => '大小',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"max":"大","min":"小"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '1',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '8',
                'binary_currency_id' => '3',// 期權-幣種id
                'binary_rule_type_id' => '2',// 二元期權-規則-類型id
                'name' => '單雙',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"odd":"單","even":"雙"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '2',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '9',
                'binary_currency_id' => '3',// 期權-幣種id
                'binary_rule_type_id' => '3',// 二元期權-規則-類型id
                'name' => '漲跌',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"rise":"漲","fall":"跌"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '3',// 排序
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '10',
                'binary_currency_id' => '4',// 期權-幣種id
                'binary_rule_type_id' => '1',// 二元期權-規則-類型id
                'name' => '大小',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"max":"大","min":"小"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '1',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '11',
                'binary_currency_id' => '4',// 期權-幣種id
                'binary_rule_type_id' => '2',// 二元期權-規則-類型id
                'name' => '單雙',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"odd":"單","even":"雙"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '2',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '12',
                'binary_currency_id' => '4',// 期權-幣種id
                'binary_rule_type_id' => '3',// 二元期權-規則-類型id
                'name' => '漲跌',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"rise":"漲","fall":"跌"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '3',// 排序
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '13',
                'binary_currency_id' => '5',// 期權-幣種id
                'binary_rule_type_id' => '1',// 二元期權-規則-類型id
                'name' => '大小',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"max":"大","min":"小"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '1',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '14',
                'binary_currency_id' => '5',// 期權-幣種id
                'binary_rule_type_id' => '2',// 二元期權-規則-類型id
                'name' => '單雙',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"odd":"單","even":"雙"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '2',// 排序
                'status' => '1',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
            [
                'id' => '15',
                'binary_currency_id' => '5',// 期權-幣種id
                'binary_rule_type_id' => '3',// 二元期權-規則-類型id
                'name' => '漲跌',// 名稱');
                'rule_json' => '{"source_idCode":"generalCode","GetArrayKey":"0"}',// 規則JSON
                'bet_json' => '{"rise":"漲","fall":"跌"}',// 投注選項值JSON
                'odds' => '0.96',// 賠率
                'sort' => '3',// 排序
                'status' => '0',
                'created_at' => $now_date,
                'updated_at' => $now_date,
            ],
        ]);

        // use App\Models\Binary\BinaryCurrencyTrend;
        // [測試資料]二元期權-幣種-走勢
        DB::table('binary_currency_trend')->truncate();
        // $trend = BinaryCurrencyTrend::factory()->count(50)->make();
        // BinaryCurrencyTrend::create($trend->toArray);
        BinaryCurrencyTrend::factory()->count(2000)->create();

        // [測試資料]二元期權-幣種-走勢
        // DB::table('binary_currency_trend')->truncate();
        // DB::table('binary_currency_trend')->insert([
        //     // BTC-USD
        //     [
        //         'id' => '1',
        //         'binary_currency_id' => '1',
        //         'period' => 'BTCUSDT202103301530',
        //         // 時間
        //         'bet_at' => '2021-03-30 15:00:00',
        //         'stop_at' => '2021-03-30 15:25:00',
        //         'draw_at' => '2021-03-30 15:30:00',
        //         // 走勢
        //         'draw' => '81',
        //         'trend_before' => '5445.19',
        //         'trend' => '5436.81',
        //         // 'forecast' => '[]',
        //         // 'forecast' => json_encode($forecast),
        //         'forecast' => $this->__forecast('5436.81', '01', '07'),
        //         'draw_rule_json' => '[]',
        //         // 統計(智能開獎用)
        //         'max' => '10',
        //         'min' => '20',
        //         'odd' => '15',
        //         'even' => '15',
        //         'rise' => '10',
        //         'fall' => '20',
        //         // 統計(報表用)
        //         'bet_quantity' => '30',
        //         'bet_amount' => '300.00',
        //         'draw_quantity' => '1',
        //         'draw_amount' => '20',
        //         'draw_rate' => '0.50',
        //         'redeem' => '0',
        //         // 狀態
        //         'status' => '1',
        //         'created_at' => $now_date,
        //         'updated_at' => $now_date,
        //     ],
        // ]);

        // 二元期權-幣種-圖表
        DB::table('binary_currency_chart')->truncate();
        // 資料改跑排程
        // DB::table('binary_currency_chart')->insert([
        //     [
        //         'id' => '1',
        //         'binary_currency_id' => '1',
        //         'date' => '2021-04-01',
        //         'open' => '1980.11',
        //         'close' => '5130.78',
        //         'high' => '5130.78',
        //         'low' => '4880.57',
        //         'status' => '1',
        //         'created_at' => $now_date,
        //         'updated_at' => $now_date,
        //     ],
        // ]);
    }
}
