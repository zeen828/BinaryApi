<?php

namespace App\Models\Custom;

trait EloquentGetTableNameTrait
{
    // 取得表單名稱
    public static function getTableName()
    {
        return ((new self)->getTable());
    }
}
