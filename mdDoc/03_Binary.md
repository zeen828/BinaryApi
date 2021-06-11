[回上層目錄](../README.md)

# Laravel Admin

## 1.安裝Redis套件
### 安裝Redis
```bash
# composer安裝指令
composer require predis/predis
```

---

## 2.安裝JWT套件
### 安裝JWT
```bash
# composer安裝指令
composer require tymon/jwt-auth
```

### 設定JWT的配置
```php
// 設定config\app.php
    'providers' => [
        // JWT-安裝
        Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
    ],
    'aliases' => [
        // JWT-安裝
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
    ],
// PS：這是全局引用，也可以不在全局引用在個別檔案單獨引用。
```

### 建立JWT設定檔
```bash
# Laravel安裝指令
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
# 生成config\jwt.php
```

### 建立JWT金鑰
```bash
# Laravel生成jwt使用的密鑰(寫入.env)
# JWT_SECRET=secret_jwt_string_key
php artisan jwt:secret
```

### 建立JWT授權模型(Model)
```bash
# Laravel生成Model檔案
php artisan make:model Auth/User
# 生成app\Models\Auth\User.php
```

### 設定JWT授權模型(Model)
```php
// JWT授權模型設定，範例使用app\Models\Auth\User.php
namespace App\Models\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// JWT
use Tymon\JWTAuth\Contracts\JWTSubject;

// class User extends Authenticatable
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // 資料庫名稱
    protected $table = 'users';

    // 欄位名稱
    protected $fillable = [
        'name', 'email', 'password',
    ];

    // 隱藏不顯示欄位
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 未查
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 獲取將存儲在JWT的主題聲明中的標識符。
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    // 返回一個鍵值數組，其中包含要添加到JWT的所有自定義聲明。
    public function getJWTCustomClaims() {
        return [];
    }
}
```

### 設定授權配置
```php
// 設定config\auth.php
    'defaults' => [
        'guard' => 'web',// 對應guards的陣列，驗證方式配置
        'passwords' => 'users',// 對應passwords的陣列，密碼重設配置
    ],

    'guards' => [
        'jwt' => [
            'driver' => 'jwt',// 驗證方式
            'provider' => 'auth_users',// 對應providers的陣列，驗整資料配置
        ],
    ],

    'providers' => [
        'auth_users' => [
            'driver' => 'eloquent',// 資料取得方式
            'model' => App\Models\Auth\User::class,// 模型位置
        ],
    ],

    'passwords' => [
        'auth_users' => [
            'provider' => 'auth_users',// 對應providers的陣列，驗整資料配置
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
```

### 建立JWT檢驗中間件(Middleware)
```bash
# Laravel生成Middleware檔案
php artisan make:middleware Auth/JwtMiddleware
# 生成app\Http\Middleware\Auth\JwtMiddleware.php
```

### 設定JWT檢驗中間件(Middleware)
```php
// 中間件設定，範例使用app\Http\Middleware\Auth\JwtMiddleware.php
namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
// JWT授權，個別檔案單獨引用
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
// class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                // 令牌無效
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // 令牌過期
                return response()->json(['status' => 'Token is Expired']);
            }else{
                // 找不到另台
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
```

### 註冊JWT檢驗中間件(Middleware)
```php
// 註冊中間件app\Http\Kernel.php
    protected $routeMiddleware = [
        // JWT
        'jwt.verify' => \App\Http\Middleware\Auth\JwtMiddleware::class,
    ];
```

### 路由配置
```php
// 修改路由routes\api.php
Route::group([
    'middleware' => ['jwt.verify'],
    'prefix' => 'v1',
    'as' => 'api.v1',
], function ($router) {
    // 功能
    // ...
});
```

---

## 3.Laravel建置檔案
### 遷移
#### [建立]遷移
```bash
php artisan make:migration Test --path="database/migrations/20210310"
# 網站
php artisan make:migration Website --path="database/migrations/20210310"
php artisan make:migration WebsiteDealer --path="database/migrations/20210310"
php artisan make:migration WebsiteBulletin --path="database/migrations/20210310"
# 用戶
php artisan make:migration User --path="database/migrations/20210315"
php artisan make:migration UserBetting --path="database/migrations/20210315"
# 訂單
php artisan make:migration Order --path="database/migrations/20210315"
# 二元
php artisan make:migration Binary --path="database/migrations/20210320"
php artisan make:migration BinaryCurrency --path="database/migrations/20210320"
php artisan make:migration BinaryCurrencyTrend --path="database/migrations/20210320"
php artisan make:migration BinaryCurrencyChart --path="database/migrations/20210320"
php artisan make:migration BinaryRuleType --path="database/migrations/20210320"
php artisan make:migration BinaryRuleCurrency --path="database/migrations/20210320"
```

#### [`執行`]遷移
```bash
php artisan migrate --path="database\migrations\20210310"
php artisan migrate --path="database\migrations\20210315"
php artisan migrate --path="database\migrations\20210320"
```

---

### 資料匯入
#### [建立]資料匯入
```bash
php artisan make:seeder Binary01DataSeeder
```

#### [`執行`]資料匯入
```bash
php artisan db:seed --class=Binary01DataSeeder
```

### [建立]工廠模式
```bash
php artisan make:factory Binary\BinaryCurrencyTrend --model=Binary\BinaryCurrencyTrend
```

### [使用]工廠模式
```php
use App\Models\Binary\BinaryCurrencyTrend;
    BinaryCurrencyTrend::factory()->count(500)->create();
```

---
### 模型
#### [建立]模型
```bash
# 網站
php artisan make:model Website/Website
php artisan make:model Website/Dealer
php artisan make:model Website/Bulletin
# 用戶
php artisan make:model User/User
php artisan make:model User/UserBetting
# 訂單
php artisan make:model Order/Order
# 二元
php artisan make:model Binary/Binary
php artisan make:model Binary/BinaryCurrency
php artisan make:model Binary/BinaryCurrencyTrend
php artisan make:model Binary/BinaryRuleType
php artisan make:model Binary/BinaryRuleCurrency
php artisan make:model Binary/BinaryCurrencyChart
```

---

### 控制器
#### [建立]控制器
```bash
php artisan make:controller Api/DockingController
php artisan make:controller Api/UserController
php artisan make:controller Api/BinaryController
php artisan make:controller Api/CurrencyController
php artisan make:controller Api/BettingController
```

---

### 回傳格式
#### [建立]回傳格式
```bash
# 對接會員 - 授權(單筆)
php artisan make:resource Api/Jwt/TokenResource
php artisan make:resource Api/Jwt/TokenCollection --collection

# 會員 - 資訊(單筆)
php artisan make:resource Api/User/UserInfoResource
php artisan make:resource Api/User/UserInfoCollection --collection
# 會員 - 紀錄 - 訂單(多筆)
php artisan make:resource Api/User/RecordOrderResource
php artisan make:resource Api/User/RecordOrderCollection --collection
# 會員 - 紀錄 - 投注(多筆)
php artisan make:resource Api/User/RecordBettingResource
php artisan make:resource Api/User/RecordBettingCollection --collection

# 點數 - 訂單
php artisan make:resource Api/Point/OrderResource
php artisan make:resource Api/Point/OrderCollection --collection

# 期權 - 清單(多筆)
php artisan make:resource Api/Binary/BinaryResource
php artisan make:resource Api/Binary/BinaryCollection --collection

# 期權 - 清單(多筆)
php artisan make:resource Api/Currency/CurrencyResource
php artisan make:resource Api/Currency/CurrencyCollection --collection
# 期權 - 走勢 - 每分鐘(多筆)
php artisan make:resource Api/Currency/TrendMinuteResource
php artisan make:resource Api/Currency/TrendMinuteCollection --collection
# 期權 - 走勢 - 開獎(多筆)
php artisan make:resource Api/Currency/TrendDrawResource
php artisan make:resource Api/Currency/TrendDrawCollection --collection
# 期權 - 圖表 - 走勢(多筆)
php artisan make:resource Api/Currency/ChartTrendResource
php artisan make:resource Api/Currency/ChartTrendCollection --collection
# 期權 - 圖表 - K線圖(多筆)
php artisan make:resource Api/Currency/ChartKResource
php artisan make:resource Api/Currency/ChartKCollection --collection

# 投注 - 規則 - 類型
php artisan make:resource Api/Betting/BettingRuleTypeResource
php artisan make:resource Api/Betting/BettingRuleTypeCollection --collection
# 投注 - 規則 - 幣種
php artisan make:resource Api/Betting/BettingRuleCurrencyResource
php artisan make:resource Api/Betting/BettingRuleCurrencyCollection --collection
# 投注 - 下注(單筆)
php artisan make:resource Api/Betting/BettingInfoResource
php artisan make:resource Api/Betting/BettingInfoCollection --collection
```

---

### 例外處理
#### [建立]例外處理
```bash
# 函式庫-期權開獎-例外處理
php artisan make:exception Libraries/BinaryDrawException
# 第三方對接(平台)
php artisan make:exception Third/ThirdException
# JWT-例外處理
php artisan make:exception Auth/JwtException
# JWT-例外處理
php artisan make:exception Auth/DockingException
# 會員-例外處理
php artisan make:exception Binary/UserException
# 二元期權-例外處理
php artisan make:exception Binary/BinaryException
# 投注-例外處理
php artisan make:exception Binary/BettingException
# PS：會覆蓋不要重複使用
```

---

### 排程規劃
手動 - 更新期權資料
手動 - 更新期權-幣種資料
每天凌晨 -  今天(1號)產生後天(2號)的明天的開獎走勢期數
每天每分鐘 - 目前開獎ID(1)查詢API走勢值存在(3)-預處理3筆
每天每分鐘 - 目前開獎ID(1)透過LOG智能開獎

走勢 - 拉取第一筆資料[1]，如果第一筆資料[1]剩10個拉取第二筆資料[2]
where('id', '>', 1)-orderby('draw_at', 'asc')->f

### 命令指令
#### [建立]命令指令
```bash
# 透過nomics的API來建立/更新資料庫[二元期權資料]資料.
php artisan make:command Nomics/GetBinaryJob

# 整理乙存資料庫[二元期權資料]資料分類幣種來建立/更新資料庫[二元期權資料 - 幣種]資料.
php artisan make:command Nomics/GetCurrencyJob

# 整理乙存資料庫[二元期權資料 - 幣種]資料預先處理走勢週期來建立/更新資料庫[二元期權資料 - 幣種 - 走勢]資料.
php artisan make:command Binary/GetTrendJob

# 透過nomics的API來取得當下走勢資料更新資料庫[二元期權資料 - 幣種 - 走勢]資料.
php artisan make:command Binary/GetTrendDrawJob

# 開獎(狀態0轉1)
php artisan make:command Binary/GetDrawStatusJob

# 開獎(狀態0轉1)
php artisan make:command Trend/LoopWSJob

php artisan make:command Trend/MatchAwardsJob

# 整理乙存資料庫[二元期權資料 - 幣種 - 走勢]資料，轉換成每日K線圖
php artisan make:command Chart/GetTrendToKJob

# 測試用
php artisan make:command Tests/loopJob
```

#### [`執行`]命令指令
```bash
# 透過nomics的API來建立/更新資料庫[二元期權資料]資料.
php artisan nomics:getBinary

# 整理乙存資料庫[二元期權資料]資料分類幣種來建立/更新資料庫[二元期權資料 - 幣種]資料.
php artisan nomics:getCurrency

# 整理已存資料庫[二元期權資料 - 幣種]資料預先處理走勢週期來建立/更新資料庫[二元期權資料 - 幣種 - 走勢]資料.
php artisan binary:getTrend
# 昨天
php artisan binary:getTrend --period=yesterday
# 今天
php artisan binary:getTrend --period=today
# 明天(預設)
php artisan binary:getTrend --period=tomorrow

# 透過nomics的API來取得當下走勢資料更新資料庫[二元期權資料 - 幣種 - 走勢]資料.
php artisan binary:getTrendDraw

# 走勢資料轉換成圖表資料
php artisan chart:getTrendToK
php artisan chart:getTrendToK --period=2021-05-05

```

---

## 4.Admin控制器
### [建立]Admin控制器
```bash
php artisan admin:make Website/WebsiteController --model=App\Models\Website\Website
php artisan admin:make Website/DealerController --model=App\Models\Website\Dealer
php artisan admin:make Website/BulletinController --model=App\Models\Website\Bulletin

php artisan admin:make User/UserController --model=App\Models\User\User
php artisan admin:make User/BettingController --model=App\Models\User\UserBetting

php artisan admin:make Order/EnterController --model=App\Models\Order\Order
php artisan admin:make Order/OutController --model=App\Models\Order\Order
php artisan admin:make Order/BetController --model=App\Models\Order\Order

php artisan admin:make Binary/BinaryController --model=App\Models\Binary\Binary
php artisan admin:make Binary/CurrencyController --model=App\Models\Binary\BinaryCurrency
php artisan admin:make Binary/RuleController --model=App\Models\Binary\BinaryRuleCurrency
php artisan admin:make Binary/TrendController --model=App\Models\Binary\BinaryCurrencyTrend
```

---

## [`執行`]路由查詢
```bash
php artisan route:list
```

## 運行
```bash
php artisan serve
```

## 開發指令
### 建立::Admin Controller
```bash
php artisan admin:make 目錄/檔案名稱Controller --model=App\Models\目錄\檔案名稱
```
