<?php

namespace App\Models\Binary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class BinaryCurrency extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'binary_currency';
    // 欄位名稱
    protected $fillable = [
        'binary_id', 'binary_name', 'binary_code', 'currency_name', 'currency_code',
        // 走勢
        'trend_data_json', 'trend_digits', 'trend_repeat',
        // 預報
        'forecast_data_json', 'forecast_digits', 'forecast_repeat',
        // 運作時間
        'week', 'start_t', 'end_t', 'stop_enter', 'repeat', 'reservation', 'win_rate',
        'sort',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];

    /**
     * 1:n(反向) 二元期權
     *
     * @return void
     */
    public function rBinary()
    {
        return $this->belongsTo('App\Models\Binary\Binary', 'binary_id', 'id');
    }

    /**
     * 1:n 二元期權 - 幣種 - 走勢
     *
     * @return void
     */
    public function rTrend()
    {
        return $this->hasMany('App\Models\Binary\BinaryCurrencyTrend', 'binary_currency_id', 'id');
    }
}
