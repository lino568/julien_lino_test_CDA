<?php

namespace App\Controllers\EntityControllers;

use App\Controllers\Controller;
use App\Models\Impl\TypeMaterielDAOImpl;
use App\Models\Interfaces\TypeMaterielDAO;

class TypeMaterielController extends Controller
{
    private TypeMaterielDAO $typeMaterielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->typeMaterielDAO = new TypeMaterielDAOImpl();
    }

    public function create()
    {
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue.');
        }

        if (empty($_POST['libelle']) || empty($_POST['idCategorie']) || empty($_POST['quantite'])) {
            $this->redirectTo('/gestion-materiel', 'error', 'Veuillez remplir les champs.');
        }

        if ($_POST['quantite'] <= 0) {
            $this->redirectTo('/gestion-materiel', 'error', 'La quantité de matériel ne doit pas être inférieure ou égale à 0.');
        }

         $resultOfInsertInDB = $this->typeMaterielDAO->create($_POST);

        if ($resultOfInsertInDB) {
            $this->redirectTo('/gestion-materiel', 'success', 'Le type de matériel a été créée avec succès.');
        }

        $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue.');
    }
}