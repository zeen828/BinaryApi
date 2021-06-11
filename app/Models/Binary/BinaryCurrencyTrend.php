<?php

namespace App\Models\Binary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class BinaryCurrencyTrend extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'binary_currency_trend';
    // 欄位名稱
    protected $fillable = [
        'binary_currency_id', 'period',
        // 時間
        'bet_at', 'stop_at', 'draw_at',
        // 走勢
        'draw', 'trend_before', 'trend_api', 'trend', 'forecast', 'draw_rule_json',
        // 統計(智能開獎用)
        'max', 'min', 'odd', 'even', 'rise', 'fall',
        // 統計(報表用)
        'bet_quantity', 'bet_amount', 'draw_quantity', 'draw_amount', 'draw_rate', 'redeem',
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
    public function rCurrency()
    {
        return $this->belongsTo('App\Models\Binary\BinaryCurrency', 'binary_currency_id', 'id');
    }
}
