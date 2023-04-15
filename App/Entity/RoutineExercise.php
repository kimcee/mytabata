<?php

namespace App\Entity;

use App\System\Entity;

class RoutineExercise extends Entity {
    public static $table_name = "routine_exercises";

    public static function getExercises(int $routineId = 0)
    {
        $tableName = self::$table_name;
        $sql = "SELECT 
                    r.id, e.id as 'exercise_id',
                    e.name as 'exercise_name'
                FROM {$tableName} r
                JOIN exercises e ON e.id = r.exercise
                WHERE r.routine = ?
                ORDER BY r.id ASC";

        return self::query($sql, [$routineId]);
    }
}