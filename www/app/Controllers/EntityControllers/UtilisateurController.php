<?php

namespace App\Controllers\EntityControllers;

use App\Controllers\Controller;
use App\Models\Impl\UtilisateurDAOImpl;
use App\Models\Interfaces\UtilisateurDAO;

class UtilisateurController extends Controller
{
    private UtilisateurDAO $utilisateurDAO;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurDAO = new UtilisateurDAOImpl();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('/gestion-utilisateur', 'error', 'Une erreur est survenue');
        }

        if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['motDePasse']) || empty($_POST['role'])) {
            $this->redirectTo('/gestion-utilisateur', 'error', 'Veuillez remplir tous les champs');
        }

        $responseOfInsertUserInDatabase = $this->utilisateurDAO->create($_POST);

        if ($responseOfInsertUserInDatabase) {
            $this->redirectTo('/gestion-utilisateur', 'success', 'L\'utilisateur a été enregistré avec succès');
        }

        $this->redirectTo('/gestion-utilisateur', 'error', 'Une  erreur est survenue');

    }

    public function modify(int $idUtilisateur)
    {
        dd($idUtilisateur);
    }

    public function delete(int $idUtilisateur)
    {

    }
}