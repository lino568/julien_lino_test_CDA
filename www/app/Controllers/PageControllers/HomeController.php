<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view("WelcomePageView", [
            'title' => "accueil"
        ]);
    }
}