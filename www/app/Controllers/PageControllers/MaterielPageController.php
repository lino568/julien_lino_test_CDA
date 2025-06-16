<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Enums\EtatMateriel;
use App\Models\Impl\MaterielDAOImpl;
use App\Models\Interfaces\MaterielDAO;

class MaterielPageController extends Controller
{
    private MaterielDAO $materielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->materielDAO = new MaterielDAOImpl();
    }

    public function index(int $id)
    {
        $materiel = $this->materielDAO->findById($id);

        if (!$materiel) {
            $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue');
        }

        $listEtatMateriel = EtatMateriel::getArrayOfEnumValue();

        $this->view('PageMaterielView', [
            'title' => "Fiche matériel",
            'materiel' => $materiel,
            'listeEtat' => $listEtatMateriel
        ]);
    }
}