<?php

namespace App\Models\Binary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class BinaryRuleCurrency extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'binary_rule_currency';
    // 欄位名稱
    protected $fillable = [
        'binary_currency_id', 'binary_rule_type_id', 'name', 'rule_json', 'bet_json', 'odds', 'sort',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];

    /**
     * 1:1 二元期權 - 幣種
     *
     * @return void
     */
    public function rCurrency()
    {
        return $this->hasOne('App\Models\Binary\BinaryCurrency', 'id', 'binary_currency_id');
    }
}
