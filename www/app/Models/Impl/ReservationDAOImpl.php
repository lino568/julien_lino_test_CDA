<?php

namespace App\Models\Impl;

use App\Enums\StatutReservation;
use PDO;
use Config\Log;
use PDOException;
use Config\Database;
use App\Models\Reservation;
use App\Factory\UtilisateurFacory;
use App\Factory\ReservationFactory;
use App\Models\Interfaces\MaterielDAO;
use App\Models\Interfaces\ReservationDAO;
use App\Models\Interfaces\UtilisateurDAO;

class ReservationDAOImpl implements ReservationDAO
{
    private PDO $db;
    private UtilisateurDAO $utilisateurDAO;
    private MaterielDAO $materielDAO;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->utilisateurDAO = new UtilisateurDAOImpl();
        $this->materielDAO = new MaterielDAOImpl();
    }

    const CREATE_SQL = "INSERT INTO reservations (dateDebutReservation, dateFinReservation, statut, idUtilisateur) VALUES (:dateDebutReservation, :dateFinReservation, :statut, :idUtilisateur)";
    const MAKE_LINK_RESERVATION_MATERIEL_SQL = "INSERT INTO reservations_materiels (idReservation, idMateriels) VALUES (:idReservation, :idMateriels)";
    const FIND_BY_ID_RESERVATION_SQL = "SELECT * FROM reservations WHERE idReservation = :idReservation";
    const FIND_ALL_SQL = "SELECT r.*, u.nom, u.prenom, COUNT(rm.idMateriels) AS number_of_materials, tm.libelle
        FROM reservations r
        JOIN Utilisateurs u ON r.idUtilisateur = u.idUtilisateur
        JOIN reservations_materiels rm ON r.idReservation = rm.idReservation
        JOIN materiels m ON rm.idMateriels = m.idMateriels
        JOIN typeMateriels tm ON m.idTypeMateriel = tm.idTypeMateriel
        GROUP BY r.idReservation, u.nom, u.prenom, tm.libelle
        ORDER BY r.dateDebutReservation DESC";
    const FIND_ALL_BY_ID_UTILISATEUR_SQL = "SELECT r.*, COUNT(rm.idMateriels) AS number_of_materials, tm.libelle
        FROM reservations r
        JOIN reservations_materiels rm ON r.idReservation = rm.idReservation
        JOIN materiels m ON rm.idMateriels = m.idMateriels
        JOIN typeMateriels tm ON m.idTypeMateriel = tm.idTypeMateriel
        WHERE r.idUtilisateur = :idUtilisateur
        GROUP BY r.idReservation, tm.libelle
        ORDER BY r.dateDebutReservation DESC";
    const FIND_ALL_BY_ID_TYPE_MATERIEL_SQL = "SELECT re.idReservation, re.dateDebutReservation, re.dateFinReservation, re.statut, COUNT(rema.idMateriels) AS nbMaterielsReserves, u.nom AS utilisateurNom, u.prenom AS utilisateurPrenom
        FROM reservations re
        JOIN reservations_materiels rema ON re.idReservation = rema.idReservation
        JOIN materiels ma ON rema.idMateriels = ma.idMateriels
        JOIN Utilisateurs u ON re.idUtilisateur = u.idUtilisateur
        WHERE ma.idTypeMateriel = :idTypeMateriel
        GROUP BY re.idReservation, re.dateDebutReservation, re.dateFinReservation, re.statut, u.nom, u.prenom";

    const UDPDATE_SQL = "UPDATE reservations SET dateDebutReservation = :dateDebutReservation, dateFinReservation = :dateFinReservation, statut = :statut WHERE idReservation = :idReservation";
    const DELETE_SQL = "DELETE FROM reservations WHERE idReservation = :idReservation";
    const DELETE_LINK_SQL = "DELETE FROM reservations_materiels WHERE idReservation = :idReservation";

    public function create(int $dateDebut, int $dateFin, int $idUtilisateur, int $idTypeMateriel, int $quantite): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(self::CREATE_SQL);
            $stmt->execute([
                ':dateDebutReservation' => $dateDebut,
                ':dateFinReservation' => $dateFin,
                ':statut' => StatutReservation::EN_COURS->value,
                ':idUtilisateur' => $idUtilisateur
            ]);

            $lastInsertId = $this->db->lastInsertId();

            $getAllMaterialAvailable = $this->materielDAO->findAllMaterialAvailable($idTypeMateriel, $dateDebut, $dateFin);

            $this->createLink($lastInsertId, $getAllMaterialAvailable, $quantite);
            
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            Log::getLogger()->warning('La méthode create de ReservationDAO a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function createLink(int $idReservation, array $ArrayAvailableMateriels, int $quantite): bool
    {
        $insertStatus = true;
        $rowInserted = 0;
        try {
            $stmtLink = $this->db->prepare(self::MAKE_LINK_RESERVATION_MATERIEL_SQL);
            foreach ($ArrayAvailableMateriels as $availableMaterial) {
                if ($rowInserted === $quantite) {
                    break;
                }
                $insertStatus = $stmtLink->execute([
                    ':idReservation' => $idReservation,
                    ':idMateriels' => $availableMaterial->getIdMateriel()
                ]);
                $rowInserted++;
            }
            if ($insertStatus) {
                return true;
            }
            return false;

        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode createLink de ReservationDAO a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function findByIdReservation(int $idReservation): ?Reservation
    {
        try {
            $stmt = $this->db->prepare(self::FIND_BY_ID_RESERVATION_SQL);
            $stmt->execute([
                ':idReservation' => $idReservation
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $utilisateur = $this->utilisateurDAO->findByIdUtilisateur($result['idUtilisateur']);
                if ($utilisateur) {
                    return ReservationFactory::create($result, $utilisateur);
                }
            }

            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findByIdReservation de ReservationDAO a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findAll(): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_ALL_SQL);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                $arrayOfReservationObject = [];
                foreach ($results as $reservation) {
                    $arrayOfReservationObject[] = $reservation;
                }
                return $arrayOfReservationObject;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAll de ReservationDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findAllByIdUtilisateur(int $idUtilisateur): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_ALL_BY_ID_UTILISATEUR_SQL);
            $stmt->execute([
                ':idUtilisateur' => $idUtilisateur
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                $arrayOfReservationObject = [];
                foreach ($results as $reservation) {
                    $arrayOfReservationObject[] = $reservation;
                }
                return $arrayOfReservationObject;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAllByIdUtilisateur de ReservationDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findAllByIdTypeMateriel(int $idTypeMateriel): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_ALL_BY_ID_TYPE_MATERIEL_SQL);
            $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                $arrayOfReservation = [];
                foreach ($results as $reservation) {
                    $arrayOfReservationObject[] = $reservation;
                }
                return $arrayOfReservationObject;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAllByIdMateriel de ReservationDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function update(array $data): bool
    {
        try {
            $stmt = $this->db->prepare(self::UDPDATE_SQL);
            return $stmt->execute([
                ':dateDebutReservation' => $data['dateDebutReservation'],
                ':dateFinReservation' => $data['dateFinReservation'],
                ':statut' => $data['statut'],
                ':idReservation' => $data['idReservation']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode update de ReservationDAOImpl a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $idReservation): bool
    {
        try {
            $this->db->beginTransaction();
            
            $this->deleteLink($idReservation);

            $stmt = $this->db->prepare(self::DELETE_SQL);
            $stmt->execute([
                ':idReservation' => $idReservation
            ]);


            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            Log::getLogger()->warning('La méthode delete de ReservationDAOImpl a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function deleteLink(int $idReservation): bool
    {
        try {
            $stmtLink = $this->db->prepare(self::DELETE_LINK_SQL);
            return $stmtLink->execute([
                ':idReservation' => $idReservation
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode deleteLink de ReservationDAOImpl a échoué : ' . $e->getMessage());
            return false;
        }
    }
}
