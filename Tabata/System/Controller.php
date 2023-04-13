<?php

namespace Tabata\System;

use Tabata\Entity\User;

class Controller {

    private $loggedInUser;

    public function __construct(?User $user = null)
    {
        $this->loggedInUser = $user;
    }

    public function view($page, $data = [])
    {
        $CONTENT = '';

        foreach ($data as $var => $val) {
            $$var = $val;
        }

        if ($this->loggedInUser) {
            $user = $this->loggedInUser;
        } else {
            $user = null;
        }


        ob_start();
            include("Views/{$page}.php");
            $CONTENT = ob_get_contents();
        ob_end_clean();

        include("Views/base.php");
        exit;
    }

    public function ajax($array = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($array);
        exit;
    }

}