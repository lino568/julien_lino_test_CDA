<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Enums\StatutReservation;
use App\Models\Impl\TypeMaterielDAOImpl;
use App\Models\Interfaces\TypeMaterielDAO;

class CreationReservationController extends Controller
{
    private TypeMaterielDAO $typeMaterielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->typeMaterielDAO = new TypeMaterielDAOImpl();
    }

    public function index()
    {
        $listOfStatut = StatutReservation::getArrayOfEnumValue();

        $connectedUser = $this->session::get('user');

        $listOfTypeMateriel = $this->typeMaterielDAO->findAll();

        $this->view('CreationReservationView', [
            'title' => "Création de réservation",
            'listStatut' => $listOfStatut,
            'idUtilisateur' => $connectedUser->getIdUtilisateur(),
            'listTypeMateriel' => $listOfTypeMateriel
        ]);
    }
}