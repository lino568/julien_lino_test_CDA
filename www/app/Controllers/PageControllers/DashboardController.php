<?php

namespace App\Controllers\PageControllers;

use App\Controllers\Controller;
use App\Models\Impl\TypeMaterielDAOImpl;
use App\Models\Interfaces\TypeMaterielDAO;

class DashboardController extends Controller
{
    private TypeMaterielDAO $typeMaterielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->typeMaterielDAO = new TypeMaterielDAOImpl();
    }

    public function index()
    {
        $connectedUser = $this->session->get('user');
        $typeMateriels = $this->typeMaterielDAO->findAllWithAvailabeMaterial();

        $this->view('DashboardView', [
            'title' => 'Tableau de bord',
            'typeMateriels' => $typeMateriels,
            'user' => $connectedUser
        ]);
    }
}