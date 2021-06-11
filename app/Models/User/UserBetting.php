<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class UserBetting extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'user_betting';
    // 欄位名稱
    protected $fillable = [
        'user_id', 'binary_currency_id', 'binary_currency_trend_id', 'binary_rule_currency_id', 'binary_rule_currency_value',
        'quantity', 'amount', 'profit', 'win_sys', 'win_user',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];

    /**
     * 1:1 投注用戶
     *
     * @return void
     */
    public function rUser()
    {
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

    /**
     * 1:1 二元期權 - 幣種
     *
     * @return void
     */
    public function rCurrency()
    {
        return $this->hasOne('App\Models\Binary\BinaryCurrency', 'id', 'binary_currency_id');
    }

    /**
     * 1:1 二元期權 - 規則 - 幣種
     *
     * @return void
     */
    public function rRuleCurrency()
    {
        return $this->hasOne('App\Models\Binary\BinaryRuleCurrency', 'id', 'binary_rule_currency_id');
    }

    /**
     * 1:1 二元期權 - 幣種 - 走勢
     *
     * @return void
     */
    public function rCurrencyTrend()
    {
        return $this->hasOne('App\Models\Binary\BinaryCurrencyTrend', 'id', 'binary_currency_trend_id');
    }
}
