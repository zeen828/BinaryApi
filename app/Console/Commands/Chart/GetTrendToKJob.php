<?php

namespace App\Console\Commands\Chart;

use Illuminate\Console\Command;
// 模型
use App\Models\Binary\BinaryCurrency;
use App\Models\Binary\BinaryCurrencyTrend;
use App\Models\Binary\BinaryCurrencyChart;
// 時間
use Carbon\Carbon;

class GetTrendToKJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan chart:getTrendToK
     * php artisan chart:getTrendToK --period=2021-05-05
     * 
     * @var string
     */
    protected $signature = 'chart:getTrendToK {--period= : 運行周期[yesterday昨天(預設), today今天, 2021-01-01指定]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Binary-Chart]';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 開始消息
        $this->line('[' . date('Y-m-d H:i:s') . '] START');

        $period = (empty($this->option('period')))? 'yesterday' : $this->option('period');
        $this->info('[' . date('Y-m-d H:i:s') . '] Option input period：' . $period);
        // 選項走勢
        // 運行周期[yesterday昨天(預設), today今天, 2021-01-01指定]
        switch ($period)
        {
            case 'yesterday':
                // 昨天
                $baseCarbon = Carbon::yesterday();
                break;
            case 'today':
                // 今天
                $baseCarbon = Carbon::today();
                break;
            default:
                // 自訂日期
                $baseCarbon = Carbon::parse($period);
                break;
        }
        // 轉換查詢日期
        $query_start_at = $baseCarbon->format('Y-m-d 00:00:00');
        $query_end_at = $baseCarbon->format('Y-m-d 23:59:59');

        // exit();
        // $drawDate->format('YmdHi');

        // 取資料庫資料(二元期權)
        $this->info('[' . date('Y-m-d H:i:s') . '] Binary Model Data!');
        $currencies = BinaryCurrency::where('status', '1')->get();
        if(!$currencies->isEmpty())
        {
            foreach($currencies as $currency)
            {
                // print_r($currency);
                $data = [
                    'binary_currency_id' => $currency->id,
                    'date' => $baseCarbon->format('Y-m-d'),
                    'status' => '1',
                ];
                // 查詢主條件
                $trend = BinaryCurrencyTrend::where('binary_currency_id', $currency->id)->whereBetween('draw_at', array($query_start_at, $query_end_at))->where('status', '1');
                // print_r($trend->toSql());
                // print_r($trend->getBindings());
                // 統計(智能開獎用)
                $data['max'] = $trend->sum('max');
                $data['min'] = $trend->sum('min');
                $data['odd'] = $trend->sum('odd');
                $data['even'] = $trend->sum('even');
                $data['rise'] = $trend->sum('rise');
                $data['fall'] = $trend->sum('fall');
                // 統計(報表用)
                $data['bet_quantity'] = $trend->sum('bet_quantity');
                $data['bet_amount'] = $trend->sum('bet_amount');
                $data['draw_quantity'] = $trend->sum('draw_quantity');
                $data['draw_amount'] = $trend->sum('draw_amount');
                $data['draw_rate'] = (empty($data['bet_quantity']) || empty($data['draw_quantity']))? 0 : sprintf('%.2f', $data['draw_quantity'] / $data['bet_quantity']);
                // 統計(K線用)
                $high = $trend->max('trend');
                $data['high'] = (empty($high))? 0 : $high;
                $low = $trend->min('trend');
                $data['low'] = (empty($low))? 0 : $low;
                //
                $open = BinaryCurrencyTrend::select('trend')->where('binary_currency_id', $currency->id)->whereBetween('draw_at', array($query_start_at, $query_end_at))->where('status', '1')->orderBy('draw_at', 'asc')->limit(1)->first();
                // print_r($open);
                $data['open'] = (empty($open->trend))? 0 : $open->trend;
                $close = BinaryCurrencyTrend::select('trend')->where('binary_currency_id', $currency->id)->whereBetween('draw_at', array($query_start_at, $query_end_at))->where('status', '1')->orderBy('draw_at', 'desc')->limit(1)->first();
                // print_r($close);
                $data['close'] = (empty($close->trend))? 0 : $close->trend;
                // print_r($data);

                // 檢查資料是否重複(二元期權)
                $chart = BinaryCurrencyChart::where('binary_currency_id', $data['binary_currency_id'])->where('date', $data['date'])->first();
                if(empty($chart))
                {
                    // 不存在(二元期權)
                    $this->info('[' . date('Y-m-d H:i:s') . '] No Data! Create Binary Data!');
                    BinaryCurrencyChart::create($data);
                } else {
                    // 存在(二元期權)
                    $this->comment('[' . date('Y-m-d H:i:s') . '] Have Data! Update Binary Data!');
                    $chart->max = $data['max'];
                    $chart->min = $data['min'];
                    $chart->odd = $data['odd'];
                    $chart->even = $data['even'];
                    $chart->rise = $data['rise'];
                    $chart->fall = $data['fall'];
                    $chart->bet_quantity = $data['bet_quantity'];
                    $chart->bet_amount = $data['bet_amount'];
                    $chart->draw_quantity = $data['draw_quantity'];
                    $chart->draw_amount = $data['draw_amount'];
                    $chart->draw_rate = $data['draw_rate'];
                    $chart->open = $data['open'];
                    $chart->close = $data['close'];
                    $chart->high = $data['high'];
                    $chart->low = $data['low'];
                    $chart->status = $data['status'];
                    $chart->save();
                }
            }
        }
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
