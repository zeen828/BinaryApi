<?php

namespace App\Models\Binary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class BinaryCurrencyChart extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'binary_currency_chart';
    // 欄位名稱
    protected $fillable = [
        'binary_currency_id', 'date',
        // 統計用
        'max', 'min', 'odd', 'even', 'rise', 'fall',
        // 統計用
        'bet_quantity', 'bet_amount', 'draw_quantity', 'draw_amount', 'draw_rate',
        // K線用
        'open', 'close', 'high', 'low',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];
}
