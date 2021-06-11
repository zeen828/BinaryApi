# 寶塔軟體商店
| 軟體 | 版本 | 擴展 |
| :----: | :----: | :----: |
| Nginx | 1.18.0 | |
| PHP | 7.4 | SWOOLE4 |
| MySQL | 8.0.24 | |
| phpMyAdmin | 4.9 | |
| Redis | 6.2.1 | |
| redis数据管理工具 | 2.7 | |
| Git远程仓库部署工具 | 2.4 | |
| PM2管理器 | 5.2 | |

## 版本
2021-06-11 測試版

## 本地運作
```bash
php artisan serve
```

## GIT安裝
```bash
# 安裝套件
composer install
# Laravel設定檔
cp .env.example .env
# Laravel Key
php artisan key:generate
# jwt key
php artisan jwt:secret
# Laravel 遷移
php artisan migrate
# Laravel 期權遷移(Linuix)
php artisan migrate --path=/database/migrations/20210310
php artisan migrate --path=/database/migrations/20210315
php artisan migrate --path=/database/migrations/20210320
# Laravel 期權遷移(windows)
php artisan migrate --path="database\migrations\20210310"
php artisan migrate --path="database\migrations\20210315"
php artisan migrate --path="database\migrations\20210320"
# Laravel Admin 資料
php artisan db:seed --class=LaravelAdmin01UserSeeder
php artisan db:seed --class=LaravelAdmin02RoleSeeder
php artisan db:seed --class=LaravelAdmin03PermissionsSeeder
php artisan db:seed --class=LaravelAdmin04MenuSeeder
# Laravel 期權遷移 資料
php artisan db:seed --class=Binary01DataSeeder

# 排程
php artisan nomics:getBinary
php artisan nomics:getCurrency
php artisan binary:getTrend --period=today
php artisan binary:getTrend
php artisan binary:getTrendDraw
php artisan chart:getTrendToK
php artisan trend:loopWS
```

## 寶塔composer問題
```bash
# 1. 寶塔putenv()錯誤
# PHP禁用函式putenv、pcntl_signal、proc_open要移除

# 2.composer運行PHP版本錯誤
# 删除默认的配置
rm -f /usr/bin/php
# 将默认版本修改成想要的版本，如：7.4
ln -sf /www/server/php/74/bin/php /usr/bin/php
```

## 寶塔排程
```bash
[每日]建立明天開獎期別
03:30
/www/server/php/74/bin/php /www/wwwroot/binary_api/artisan binary:getTrend

[每日]統計昨天走勢圖，轉K線圖
02:00
/www/server/php/74/bin/php /www/wwwroot/binary_api/artisan chart:getTrendToK

[每分鐘]開獎下一期，兌獎這一期
/www/server/php/74/bin/php /www/wwwroot/binary_api/artisan binary:getTrendDraw

# [每分鐘]幣種1走勢(取消)
# /www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:loopWS --currency=1
# /www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:loopWS --currency=2
# /www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:loopWS --currency=3
# /www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:loopWS --currency=4
# /www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:loopWS --currency=5

[每分鐘]集合多工走勢
/www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:loopWS

[每分鐘]發派中獎獎金
/www/server/php/74/bin/php /www/wwwroot/binary_api/artisan trend:matchAwards
```
