<?php

namespace App\Models\Binary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class BinaryRuleType extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'binary_rule_type';
    // 欄位名稱
    protected $fillable = [
        'name', 'description', 'sort',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];
}
