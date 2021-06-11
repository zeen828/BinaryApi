<?php

namespace App\Console\Commands\Nomics;

use Illuminate\Console\Command;
// 模型
use App\Models\Binary\Binary;
use App\Models\Binary\BinaryCurrency;

class GetCurrencyJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan nomics:getCurrency
     * 
     * @var string
     */
    protected $signature = 'nomics:getCurrency';

    /**
     * The console command description.
     * 整理乙存資料庫[二元期權資料]資料分類幣種來建立/更新資料庫[二元期權資料 - 幣種]資料.
     *
     * @var string
     */
    protected $description = '[Binary-Nomics] Classification currency create "binary currency DB" by "binary DB".';

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

        // 取資料庫資料(二元期權)
        $this->info('[' . date('Y-m-d H:i:s') . '] Binary Model Data!');
        $binared = Binary::where('status', '1')->get();
        if(!$binared->isEmpty())
        {
            foreach($binared as $binary)
            {
                // $this->question('[' . date('Y-m-d H:i:s') . '] DeBug! DB Data!');
                // print_r($binary);
                // exit();
                // 檢查資料是否重複(二元期權 - 幣種)
                $currency = BinaryCurrency::where('binary_code', $binary->code)->where('currency_code', $binary->currency)->where('status', '1')->first();
                if(empty($currency))
                {
                    // 不存在(二元期權 - 幣種)
                    $this->info('[' . date('Y-m-d H:i:s') . '] No Data! Create Binary Currency Data!');
                    BinaryCurrency::create([
                        'binary_id' => $binary->id,
                        'binary_name' => $binary->name,
                        'binary_code' => $binary->code,
                        'currency_name' => $binary->currency,
                        'currency_code' => $binary->currency,
                    ]);
                } else {
                    // 存在(二元期權 - 幣種)
                    $this->comment('[' . date('Y-m-d H:i:s') . '] Have Data! Update Binary Currency Data!');
                    $currency->binary_id = $binary->id;
                    $currency->binary_name = $binary->name;
                    $currency->binary_code = $binary->code;
                    $currency->currency_name = $binary->currency;
                    $currency->currency_code = $binary->currency;
                    $currency->save();
                }
            }
        }

        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
