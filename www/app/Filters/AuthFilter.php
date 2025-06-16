<?php

namespace App\Filters;

use Config\Session;

class AuthFilter
{
    private ?Session $session;

    private function __construct()
    {
        $this->session = Session::getInstance();
    }

    public static function isLoggedIn(): void
    {
        if (self::$session::isset('isLoggedIn')) {
            header('Location: /');
            exit;
        }
    }
}