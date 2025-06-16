<?php

namespace App\Controllers\EntityControllers;

use App\Controllers\Controller;
use App\Enums\StatutReservation;
use App\Models\Impl\MaterielDAOImpl;
use App\Models\Impl\ReservationDAOImpl;
use App\Models\Interfaces\MaterielDAO;
use App\Models\Interfaces\ReservationDAO;

class ReservationController extends Controller
{
    private ReservationDAO $reservationDAO;
    private MaterielDAO $materielDAO;

    public function __construct()
    {
        parent::__construct();
        $this->materielDAO = new MaterielDAOImpl();
        $this->reservationDAO = new ReservationDAOImpl();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('/reserver-materiel', 'error', 'Une erreur est survenue');
        }

        if (
            empty($_POST['idUtilisateur']) ||
            empty($_POST['dateDebutReservation']) ||
            empty($_POST['heureDebutReservation']) ||
            empty($_POST['dateFinReservation']) ||
            empty($_POST['heureFinReservation']) ||
            empty($_POST['idTypeMateriel']) ||
            empty($_POST['quantite'])
        ) {
            $this->redirectTo('/reserver-materiel', 'error', 'Veuillez remplir tous les champs');
        }

        $dateDebut = $_POST['dateDebutReservation'] . " " . $_POST['heureDebutReservation'];
        $dateFin = $_POST['dateFinReservation'] . " " . $_POST['heureFinReservation'];

        $timestampDebut = strtotime($dateDebut);
        $timestampFin = strtotime($dateFin);

        if ($timestampDebut > $timestampFin) {
            $this->redirectTo('/reserver-materiel', 'error', 'Erreur dans les dates');
        }

        $nombreMaterielsDisponible = $this->materielDAO->checkMaterialDisponibility(intval($_POST['idTypeMateriel']), $timestampDebut, $timestampFin);

        if (!is_int($nombreMaterielsDisponible)) {
            $this->redirectTo('/reserver-materiel', 'error', 'Une erreur est survenue');
        }

        if ($nombreMaterielsDisponible >= intval($_POST['quantite'])) {
            $responseInsert = $this->reservationDAO->create($timestampDebut, $timestampFin, intval($_POST['idUtilisateur']), intval($_POST['idTypeMateriel']), intval($_POST['quantite']));
            if ($responseInsert) {
                $this->redirectTo('/dashboard', 'success', 'Réservation effectuée avec succès');
            }
        } else {
            $this->redirectTo('/reserver-materiel', 'error', 'Pas assez de matériel disponible');
        }
    }

    public function getAllReservationFromIdTypeMaterial()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(false);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['selectedMaterial'])) {
            $this->response(false);
        }

        $listReservation = $this->reservationDAO->findAllByIdTypeMateriel(intval($data['selectedMaterial']));

        if (!$listReservation) {
            $this->response(false);
        }

        $tabFormatedForCalendar = [];

        foreach ($listReservation as $reservation) {
            $tabFormatedForCalendar[] = [
                'startDate' => Date('Y-m-d', $reservation['dateDebutReservation']),
                'startTime' => Date('H:i', $reservation['dateDebutReservation']),
                'endDate' => Date('Y-m-d', $reservation['dateFinReservation']),
                'endTime' => Date('H:i', $reservation['dateFinReservation']),
                'user' => $reservation['utilisateurPrenom'] . " " . $reservation['utilisateurNom'],
                'status' => $reservation['statut'],
                'quantity' => $reservation['nbMaterielsReserves']
            ];
        }

        $this->response(true, $tabFormatedForCalendar);
    }

    public function modify()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('/gestion-reservation', 'error', 'Une erreur est survenue');
        }

        if (
            empty($_POST['idUtilisateur']) ||
            empty($_POST['idReservation']) ||
            empty($_POST['dateDebutReservation']) ||
            empty($_POST['heureDebutReservation']) ||
            empty($_POST['dateFinReservation']) ||
            empty($_POST['heureFinReservation']) ||
            empty($_POST['idTypeMateriel']) ||
            empty($_POST['quantite'])
        ) {
            $this->redirectTo('/gestion-reservation/modifier/' . intval($_POST['idReservation']), 'error', 'Veuillez remplir tous les champs');
        }

        $dateDebut = $_POST['dateDebutReservation'] . " " . $_POST['heureDebutReservation'];
        $dateFin = $_POST['dateFinReservation'] . " " . $_POST['heureFinReservation'];

        $timestampDebut = strtotime($dateDebut);
        $timestampFin = strtotime($dateFin);

        if ($timestampDebut > $timestampFin) {
            $this->redirectTo('/gestion-reservation/modifier/' . intval($_POST['idReservation']), 'error', 'Erreur dans les dates');
        }

        $reservationAvantModification = $this->reservationDAO->findByIdReservation(intval($_POST['idReservation']));

        $quantityAndIdTypeFromReservation = $this->materielDAO->findQuantityOfMaterialFromIdReservation(intval($_POST['idReservation']));

        if (!$reservationAvantModification || !$quantityAndIdTypeFromReservation) {
            $this->redirectTo('/gestion-reservation', 'error', 'Une erreur est survenue');
        }

        if ($quantityAndIdTypeFromReservation['idTypeMateriel'] !== intval($_POST['idTypeMateriel'])) {

            $materielDisponible = $this->checkMaterialDisponibilityAndReturnAnArrayOfAvailableMaterial(intval($_POST['idTypeMateriel']), $timestampDebut, $timestampFin, intval($_POST['quantite']));

            if (!$materielDisponible) {
                $this->redirectTo('/gestion-reservation/modifier/' . intval($_POST['idReservation']), 'error', 'Une erreur est survenue');
            }

            $linkAndReservationUpdated = $this->updateReservationAndLink($timestampDebut, $timestampFin, intval($_POST['idReservation']), $materielDisponible, intval($_POST['quantite']));

            if ($linkAndReservationUpdated) {
                $this->redirectTo('/gestion-reservation', 'success', 'Réservation modifié avec succès');
            }

            $this->redirectTo('/gestion-reservation', 'error', 'Une erreur est survenue');
        } else {
            if ($reservationAvantModification->getDateDebutReservation() !== $timestampDebut || $reservationAvantModification->getDateFinReservation() !== $timestampFin) {
                if ($reservationAvantModification->getDateDebutReservation() !== $timestampDebut && $reservationAvantModification->getDateFinReservation() !== $timestampFin) {
                    if ($reservationAvantModification->getDateDebutReservation() >= $timestampFin || $reservationAvantModification->getDateFinReservation() <= $timestampDebut) {
                        $materielDisponible = $this->checkMaterialDisponibilityAndReturnAnArrayOfAvailableMaterial(intval($_POST['idTypeMateriel']), $timestampDebut, $timestampFin, intval($_POST['quantite']));

                        if (!$materielDisponible) {
                            $this->redirectTo('/gestion-reservation/modifier/' . intval($_POST['idReservation']), 'error', 'Matériel non disponible');
                        }

                        $linkAndReservationUpdated = $this->updateReservationAndLink($timestampDebut, $timestampFin, intval($_POST['idReservation']), $materielDisponible, intval($_POST['quantite']));

                        if ($linkAndReservationUpdated) {
                            $this->redirectTo('/gestion-reservation', 'success', 'Réservation modifié avec succès');
                        }

                        $this->redirectTo('/gestion-reservation', 'error', 'Une erreur est survenue');
                    }
                    
                }
            }
        }
    }

    private function checkMaterialDisponibilityAndReturnAnArrayOfAvailableMaterial(int $idTypeMateriel, int $dateDebut, int $DateFin, int $quantite): ?array
    {
        $nombreMaterielsDisponible = $this->materielDAO->checkMaterialDisponibility($idTypeMateriel, $dateDebut, $DateFin);

        if (!is_int($nombreMaterielsDisponible)) {
            return null;
        }

        if ($nombreMaterielsDisponible >= $quantite) {

            $materielDisponible = $this->materielDAO->findAllMaterialAvailable($idTypeMateriel, $dateDebut, $DateFin);

            return $materielDisponible;
        }
        return null;
    }

    private function updateReservationAndLink(int $dateDebut, int $dateFin, int $idReservation, array $materielDisponible, int $quantite): bool
    {
        $updateStatus = $this->reservationDAO->update([
            'dateDebutReservation' => $dateDebut,
            'dateFinReservation' => $dateFin,
            'statut' => StatutReservation::EN_COURS->value,
            'idReservation' => $idReservation
        ]);

        if ($updateStatus) {
            $deleteLinkStatus = $this->reservationDAO->deleteLink($idReservation);

            if ($deleteLinkStatus) {
                $insertNewLinkStatus = $this->reservationDAO->createLink($idReservation, $materielDisponible, $quantite);

                if ($insertNewLinkStatus) {
                    return true;
                }
            }
        }
        return false;
    }

    public function delete(int $id)
    {
        $deleteStatus = $this->reservationDAO->delete($id);

        if (!$deleteStatus) {
            $this->redirectTo('/gestion-reservation', 'error', 'Une erreur est survenue');
        }

        $this->redirectTo('/gestion-reservation', 'success', 'La réservation a été supprimé avec succès');
    }

    private function response(bool $response, ?array $reservations = null)
    {
        $responsData = [
            'success' => $response
        ];

        if ($reservations) {
            $responsData['reservations'] = $reservations;
        }

        header('Content-Type: application/json');
        echo json_encode($responsData);
        exit;
    }
}
