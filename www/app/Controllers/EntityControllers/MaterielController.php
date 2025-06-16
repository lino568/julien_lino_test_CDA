<?php

namespace App\Controllers\EntityControllers;

use Config\Log;
use App\Controllers\Controller;
use App\Models\Impl\MaterielDAOImpl;
use App\Models\Interfaces\MaterielDAO;

class MaterielController extends Controller
{
    private MaterielDAO $materielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->materielDAO = new MaterielDAOImpl();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(false, 'Une erreur est survenue.');
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['reference']) || empty($data['idTypeMateriel']) || empty($data['etat'])) {
            $this->response(false, 'Paramètre manquant');
        }

        $isInsertInDatabase = $this->materielDAO->create($data);

        if (!$isInsertInDatabase) {
            $this->response(false, 'Une erreur est survenue.');
        }

        $this->response(true);
    }

    public function modify()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue');
        }

        if (empty($_POST['idMateriels']) || empty($_POST['reference']) || empty($_POST['etat'])) {
            $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue');
        }

        $modifyStatus = $this->materielDAO->update($_POST);

        if ($modifyStatus) {
            $this->redirectTo('/gestion-materiel', 'success', 'Le matériel à bien été modifié');
        }

        $this->redirectTo('/gestion-materiel', 'error', 'Une erreur est survenue');
    }

    public function delete(int $id)
    {
        $deleteStatus = $this->materielDAO->delete($id);

        if (!$deleteStatus) {
            $this->redirectTo('/gestion-materiel/materiel/' . $id, 'error', "Un problème est survenu lors de la suppression");
        }

        $this->redirectTo('/gestion-materiel', 'success', 'Le matériel à bien été supprimé');
    }

    public function getMaterielsByTypeId()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo '<div class="alert alert-error">Méthode invalide</div>';
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['type_id'])) {
            echo '<div class="alert alert-error">Paramètre manquant</div>';
            return;
        }

        $idTypeMaterial = intval($data['type_id']);

        $arrayOfMaterialObject = $this->materielDAO->findAllMaterialWithSameType($idTypeMaterial);

        if (!$arrayOfMaterialObject) {
            echo '<div class="alert alert-error">Une erreur est survenue.</div>';
            return;
        }

        $html = "";

        foreach ($arrayOfMaterialObject as $material) {
            $html .= '<div class="col-md-4 mb-3 card-item">
            <div class="card shadow-sm">
                <a class="text-decoration-none text-body" href="/gestion-materiel/materiel/' . $material->getIdMateriel() . '">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($material->getTypeMateriel()->getLibelle()) . '</h5>
                        <div class="mb-2">
                            <strong>Référence :</strong><br>' . htmlspecialchars($material->getReference()) . '</div>
                        <div class="mb-2">
                            <strong>État :</strong><br>' . htmlspecialchars($material->getEtatMateriel()->value) . '</div>
                    </div>
                </a>
            </div>
        </div>';
        }

        echo $html;
    }

    public function checkDisponibility()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(false, 'Une erreur est survenue');
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['dateDebutReservation']) || 
        empty($data['heureDebutReservation']) ||
        empty($data['dateFinReservation']) ||
        empty($data['heureFinReservation']) ||
        empty($data['materiel']) ||
        empty($data['quantite'])) {
            $this->response(false, 'Paramètre manquant');
        }

        $dateDebut = $data['dateDebutReservation'] . " " . $data['heureDebutReservation'];
        $dateFin = $data['dateFinReservation'] . " " . $data['heureFinReservation'];

        $timestampDebut = strtotime($dateDebut);
        $timestampFin = strtotime($dateFin);

        
        $nombreMaterielsDisponible = $this->materielDAO->checkMaterialDisponibility(intval($data['materiel']), $timestampDebut, $timestampFin);

        if (!is_int($nombreMaterielsDisponible)) {
            $this->response(false, 'Une erreur est survenue');
        }

        if ($nombreMaterielsDisponible >= $data['quantite']) {
            $this->response(true);
        } else {
            $this->response(false, 'Seulement ' . strval($nombreMaterielsDisponible) . ' matériels disponibles');
        }
    }

    private function response(bool $response, ?string $message = null)
    {
        $responsData = [
            'success' => $response
        ];

        if ($message) {
            $responsData['error'] = $message;
        }

        header('Content-Type: application/json');
        echo json_encode($responsData);
        exit;
    }
}
