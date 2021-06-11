<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class Website extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'website';
    // 欄位名稱
    protected $fillable = [
        'title', 'description', 'keyword', 'ga_key', 'domain',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];
}
