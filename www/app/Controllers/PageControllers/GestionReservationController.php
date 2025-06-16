<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Enums\Role;
use App\Models\Impl\ReservationDAOImpl;
use App\Models\Interfaces\ReservationDAO;

class GestionReservationController extends Controller
{
    private ReservationDAO $reservationDAO;

    public function __construct()
    {
        parent::__construct();
        $this->reservationDAO = new ReservationDAOImpl();
    }

    public function index()
    {
        $user = $this->session->get('user');

        if ($user->getRole() === Role::ADMINISTRATEUR) {
            $reservationUser = $this->reservationDAO->findAll();
        } else {
            $reservationUser = $this->reservationDAO->findAllByIdUtilisateur($user->getIdUtilisateur());
        }

        $this->view('GestionReservationView', [
            'title' => "Gestion des réservations",
            'reservations' => $reservationUser,
            'user' => $user
        ]);
    }
}