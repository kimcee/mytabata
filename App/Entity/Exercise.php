<?php

namespace App\Entity;

use App\System\Entity;

class Exercise extends Entity {
    public static $table_name = "exercises";

    public static function getRandomItem($userId)
    {
        $tableName = self::$table_name;
        $sql = "SELECT * FROM {$tableName} WHERE user = 1 or user = ? ORDER BY RAND() LIMIT 1";

        return self::query($sql, [$userId], 1);
    }
}