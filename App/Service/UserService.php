<?php

namespace App\Service;

use App\Entity\Entity;
use App\Entity\User;

class UserService {

    public const SESSION_COOKIE_VAR = 'mytabata_user';

    public static function getActiveUser(): ?User
    {
        // check for user in session
        $userHash = @$_SESSION[self::SESSION_COOKIE_VAR];

        if (empty($userHash)) {
            // check for user in cookie
            $userHash = @$_COOKIE[self::SESSION_COOKIE_VAR];
        }

        if (empty($userHash)) {
            return null;
        }

        return User::findBy(['hash' => $userHash], 1);
    }

    public static function logoutUser($user): bool
    {
        $user->hash = '';
        $user->save();

        unset($_SESSION[self::SESSION_COOKIE_VAR]);
        setcookie(self::SESSION_COOKIE_VAR, "0", time() + ((3600 * 24) * 365));

        return true;
    }

    public static function loginUser($user): bool
    {
        if (empty($user->hash)) {
            $user->hash = uniqid();
            $user->save();
        }

        $_SESSION[self::SESSION_COOKIE_VAR] = $user->hash;
        setcookie(self::SESSION_COOKIE_VAR, $user->hash, time() + ((3600 * 24) * 365));

        return true;
    }
}