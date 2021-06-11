<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class Order extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'order';
    // 欄位名稱
    protected $fillable = [
        'sn', 'user_id', 'order_sn', 'event', 'point',
        'remarks',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];

    /**
     * 1:1 轉點用戶
     *
     * @return void
     */
    public function rUser()
    {
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }
}
