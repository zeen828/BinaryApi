<?php

namespace App\Console\Commands\Nomics;

use Illuminate\Console\Command;
// 模型
use App\Models\Binary\Binary;
// 開發
use App\Libraries\Binary\Third\NomicsApi;

class GetBinaryJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan nomics:getBinary
     * 
     * @var string
     */
    protected $signature = 'nomics:getBinary';

    /**
     * The console command description.
     * 透過nomics的API來建立/更新資料庫[二元期權資料]資料.
     *
     * @var string
     */
    protected $description = '[Binary-Nomics] Call api data create "binary DB" by nomics.';

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

        // 透過API取得資料
        $this->info('[' . date('Y-m-d H:i:s') . '] Call Api Data!');
        $api = new NomicsApi();
        $apiData = $api->getCurrencies();
        $apiData = json_decode($apiData);
        // var_dump($apiData);

        // 處理資料
        foreach($apiData as $val)
        {
            // $this->question('[' . date('Y-m-d H:i:s') . '] DeBug! Api Data!');
            // print_r($val);
            // exit();
            // 檢查資料是否重複(二元期權)
            $binary = Binary::where('code', $val->original_symbol)->first();
            if(empty($binary))
            {
                // 不存在(二元期權)
                $this->info('[' . date('Y-m-d H:i:s') . '] No Data! Create Binary Data!');
                Binary::create([
                    'name' => $val->name,
                    'code' => $val->original_symbol,
                    'logo' => $val->logo_url,
                    'description' => $val->description,
                    'website' => $val->website_url,
                    'currency' => 'USD',
                    'status' => '1',
                ]);
            } else {
                // 存在(二元期權)
                $this->comment('[' . date('Y-m-d H:i:s') . '] Have Data! Update Binary Data!');
                $binary->name = $val->name;
                $binary->code = $val->original_symbol;
                $binary->logo = $val->logo_url;
                $binary->description = $val->description;
                $binary->website = $val->website_url;
                $binary->save();
            }
        }

        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
