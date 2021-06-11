[回上層目錄](../README.md)

# Laravel Admin

## 安裝
```bash
composer require encore/laravel-admin:1.*
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan admin:install
```
`PS.資料庫需要先設定好`

## 資料帶入
```bash
php artisan db:seed --class=LaravelAdmin01UserSeeder
php artisan db:seed --class=LaravelAdmin02RoleSeeder
php artisan db:seed --class=LaravelAdmin03PermissionsSeeder
php artisan db:seed --class=LaravelAdmin04MenuSeeder
```

## 安裝工具庫
```bash
composer require "laravel-admin-ext/helpers:1.*"
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
### 建立::Admin Controller
```bash
php artisan admin:make 目錄/檔案名稱Controller --model=App\Models\目錄\檔案名稱
```
