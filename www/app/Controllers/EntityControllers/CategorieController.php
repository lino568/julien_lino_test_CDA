<?php

namespace App\Controllers\EntityControllers;

use App\Controllers\Controller;
use App\Models\Impl\CategorieDAOImpl;
use App\Models\Interfaces\CategorieDAO;

class CategorieController extends Controller
{
    private CategorieDAO $categorieDAO;

    public function __construct()
    {
        parent::__construct();
        $this->categorieDAO = new CategorieDAOImpl();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue.');
        }

        if (empty($_POST['libelle'])) {
            $this->redirectTo('/gestion-materiel', 'error', 'Veuillez remplir le champ.');
        }

        $resultOfInsertInDB = $this->categorieDAO->create($_POST['libelle']);

        if ($resultOfInsertInDB) {
            $this->redirectTo('/gestion-materiel', 'success', 'La catégorie a été créée avec succès.');
        }

        $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue.');
    }
}