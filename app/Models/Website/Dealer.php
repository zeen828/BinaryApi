<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class Dealer extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'website_dealer';
    // 欄位名稱
    protected $fillable = [
        'iv', 'name', 'key', 'ip',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];
}
