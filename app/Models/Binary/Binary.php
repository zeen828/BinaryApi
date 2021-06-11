<?php

namespace App\Models\Binary;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

class Binary extends Model
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'binary';
    // 欄位名稱
    protected $fillable = [
        'name', 'code', 'logo', 'description', 'description_zh', 'website', 'currency',
        'sort',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    // protected $hidden = [];

    /**
     * 1:n 二元期權 - 幣種
     *
     * @return void
     */
    public function rCurrency()
    {
        return $this->hasMany('App\Models\Binary\BinaryCurrency', 'binary_id', 'id');
    }
}
