<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Enums\StatutReservation;
use App\Models\Impl\MaterielDAOImpl;
use App\Models\Impl\ReservationDAOImpl;
use App\Models\Impl\TypeMaterielDAOImpl;
use App\Models\Interfaces\MaterielDAO;
use App\Models\Interfaces\ReservationDAO;
use App\Models\Interfaces\TypeMaterielDAO;

class ModifierReservationController extends Controller
{
    private ReservationDAO $reservationDAO;
    private TypeMaterielDAO $typeMaterielDAO;
    private MaterielDAO $materielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->reservationDAO = new ReservationDAOImpl();
        $this->typeMaterielDAO = new TypeMaterielDAOImpl();
        $this->materielDAO = new MaterielDAOImpl();
    }

    public function index(int $id)
    {
        $reservation = $this->reservationDAO->findByIdReservation($id);

        if (!$reservation) {
            $this->redirectTo('/gestion-reservation', 'error', 'Une erreur est survenue');
        }

        $quantityAndIdType = $this->materielDAO->findQuantityOfMaterialFromIdReservation($id);

        $listOfStatut = StatutReservation::getArrayOfEnumValue();

        $listOfTypeMateriel = $this->typeMaterielDAO->findAll();

        $this->view('ModifierReservationView', [
            'title' => "Modification de réservation",
            'quantite' => $quantityAndIdType['number_of_materials'],
            'idTypeMateriel' => $quantityAndIdType['idTypeMateriel'],
            'reservation' => $reservation,
            'listStatut' => $listOfStatut,
            'listTypeMateriel' => $listOfTypeMateriel
        ]);



    }
}