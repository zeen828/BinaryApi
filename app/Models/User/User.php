<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;

// class User extends Model
class User extends Authenticatable
{
    use HasFactory, EloquentGetTableNameTrait;

    // 資料庫名稱
    protected $table = 'users';
    // 欄位名稱
    protected $fillable = [
        'name', 'email', 'password', 'point',
        // 狀態
        'status',
    ];
    // 隱藏不顯示欄位
    protected $hidden = [
        'password', 'remember_token',
    ];
    // 未查
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
