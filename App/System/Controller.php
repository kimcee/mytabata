<?php

namespace App\System;

use App\Entity\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

#[\AllowDynamicProperties]
class Controller {

    private $loggedInUser;

    public function __construct(?User $user = null)
    {
        $this->loggedInUser = $user;
    }

    public function view($page, $data = [])
    {
        $loader = new FilesystemLoader(Config::TEMPLATES_DIR);
        $twig = new Environment($loader);

        if ($this->loggedInUser) {
            $user = $this->loggedInUser;
        } else {
            $user = null;
        }

        $twig->addGlobal('USER', $user);
        $twig->addGlobal('SESSION', $_SESSION);

        $data['user'] = $user;

        echo $twig->render($page . '.html.twig', $data);
        exit;
    }

    public function ajax($array = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($array);
        exit;
    }

}