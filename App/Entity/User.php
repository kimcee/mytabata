<?php

namespace App\Entity;

use App\System\Entity;
use App\Service\UserService;

class User extends Entity {
    public static $table_name = "users";

    public function setPassword($password = '')
    {
        if (empty($password)) {
            return;
        }

        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password = ''): bool
    {
        if (empty($password)) {
            return false;
        }

        return password_verify($password, $this->password);
    }

    public static function login(string $email = '', string $password = ''): bool
    {
        if (empty($email) || empty($password)) {
            return false;
        }

        $user = User::findBy(['email' => $email], 1);

        if ($user->verifyPassword($password)) {
            UserService::loginUser($user);
            return true;
        }

        return false;
    }

    public function insert()
    {
        // default values
        $this->dark_mode = 1;
        $this->sets = 8;
        $this->set_time = 20;
        $this->break_time = 10;
        $this->rounds = 1;
        $this->hash = '';

        parent::insert();
    }

    public function delete()
    {
        // delete routines
        $routines = Routine::findBy(['user' => $this->id]);
        foreach ($routines as $routine) {
            $routine->delete();
        }

        // delete routine exercises
        $routineExercises = RoutineExercise::findBy(['user' => $this->id]);
        foreach ($routineExercises as $routineExercise) {
            $routineExercise->delete();
        }

        // delete exercises
        $exercises = Exercise::findBy(['user' => $this->id]);
        foreach ($exercises as $exercise) {
            $exercise->delete();
        }

        // delete favorites
        $favorites = Favorite::findBy(['user' => $this->id]);
        foreach ($favorites as $favorite) {
            $favorite->delete();
        }

        // delete user
        parent::delete();
    }
}