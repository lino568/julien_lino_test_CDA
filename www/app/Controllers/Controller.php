<?php

namespace App\Controllers;

use Config\Session;

class Controller
{

    protected ?Session $session;

    protected function __construct()
    {
        $this->session = Session::getInstance();
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../Views/Layouts/Header.php';
        require_once __DIR__ . "/../Views/" . $view . ".php";
        require_once __DIR__ . "/../Views/Layouts/Footer.php";
    }

    protected function redirectTo(string $url, ?string $variable = null, ?string $message = null)
    {
        if ($variable !== null && $message !== null) {
            $this->session->set($variable, $message);
        }
        header("Location: " . filter_var($url, FILTER_SANITIZE_URL));
        exit();
    }

}