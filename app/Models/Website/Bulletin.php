<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class Bulletin extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'website_bulletin';
    // 欄位名稱
    protected $fillable = [
        'title', 'content', 'img', 'start_at', 'end_at',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];
}
