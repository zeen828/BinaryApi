<?php

namespace App\Models\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 引用外掛
use App\Models\Custom\EloquentGetTableNameTrait;
// JWT
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, EloquentGetTableNameTrait;

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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
