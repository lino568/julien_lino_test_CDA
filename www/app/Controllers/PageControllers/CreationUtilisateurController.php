<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Enums\Role;

class CreationUtilisateurController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $listOfRole = Role::getArrayOfEnumValue();

        $this->view('CreationUtilisateurView', [
            'title' => "Création d'un utilisateur",
            'listRole' => $listOfRole
        ]);
    }
}