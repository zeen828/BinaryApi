[回上層目錄](../README.md)

# Laravel 8

## 安裝
```bash
composer create-project --prefer-dist laravel/laravel binary_l8 8.*
```

## 資料庫設定
```bash
# 複製環境設定檔
cp .env.example .env
# 產生一個KEY
php artisan key:generate
```

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=資料庫名稱
DB_USERNAME=資料庫帳號
DB_PASSWORD=資料庫密碼
```

## 執行[遷移]
```bash
php artisan migrate
```

## 路由查詢
```bash
php artisan route:list
```

## 運行
```bash
php artisan serve
```

## 開發指令
### 建立::Migration
```bash
php artisan make:migration 檔案名稱 --path="database/migrations/日期目錄"
# database\migrations\日期目錄\2021_03_19_072130_檔案名稱.php
```

### 操作::Migration
```bash
# 執行migration
php artisan migrate --path="database/migrations/日期目錄"
# 回復migration
php artisan migrate:rollback --path="database/migrations/日期目錄"
```

### 建立::Seeder
```bash
php artisan make:seeder 目錄/檔案名稱
# database\seeders\目錄\檔案名稱.php
```

### 操作::Seeder
```bash
# 執行seeder
php artisan db:seed --class=檔案名稱
```

### 建立::Model
```bash
php artisan make:model 目錄/檔案名稱
# app\Models\目錄\檔案名稱.php
```

### 建立::Controller
```bash
php artisan make:controller 目錄/檔案名稱Controller
# app\Http\Controllers\目錄\檔案名稱Controller.php
```

### 清除緩存
```bash
composer clear-cache
composer dump-autoload
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```
