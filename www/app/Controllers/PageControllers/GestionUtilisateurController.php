<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Models\Impl\UtilisateurDAOImpl;
use App\Models\Interfaces\UtilisateurDAO;

class GestionUtilisateurController extends Controller
{
    private UtilisateurDAO $utilisateurDAO;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurDAO = new UtilisateurDAOImpl();
    }

    public function index()
    {
        $listUtilisateurs = $this->utilisateurDAO->findAll();

        $this->view('GestionUtilisateurView', [
            'title' => 'Gestion des utilisateurs',
            'utilisateurs' => $listUtilisateurs
        ]);
    }
}