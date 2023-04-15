<?php

namespace App\Entity;

use App\System\Entity;

class Routine extends Entity {
    public static $table_name = "routines";

    public function insert()
    {
        if (empty($this->sets)) {
            $this->sets = 8;
        }

        if (empty($this->sets_time)) {
            $this->sets_time = 20;
        }

        if (empty($this->break_time)) {
            $this->break_time = 10;
        }

        parent::insert();
    }
}