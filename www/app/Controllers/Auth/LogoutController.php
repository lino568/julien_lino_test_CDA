<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;

class LogoutController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        $this->session->destroy();

        $this->redirectTo('/');
    }
}