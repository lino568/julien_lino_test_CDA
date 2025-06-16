<?php

namespace App\Models\Impl;

use App\Enums\EtatMateriel;
use PDO;
use Config\Log;
use PDOException;
use Config\Database;
use App\Models\Materiel;
use App\Factory\MaterielFactory;
use App\Models\Interfaces\MaterielDAO;
use App\Models\Interfaces\TypeMaterielDAO;

class MaterielDAOImpl implements MaterielDAO
{
    private PDO $db;
    private TypeMaterielDAO $typeMaterielDAO;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->typeMaterielDAO = new TypeMaterielDAOImpl();
    }

    const CREATE_SQL = "INSERT INTO materiels (reference, etat, idTypeMateriel) VALUES (:reference, :etat, :idTypeMateriel)";
    const FIND_BY_ID_SQL = "SELECT * FROM materiels WHERE idMateriels = :idMateriels";
    const FIND_ALL_SQL = "SELECT * FROM materiels";
    const FIND_ALL_MATERIAL_WITH_SAM_TYPE_SQL = "SELECT * FROM materiels WHERE idTypeMateriel = :idTypeMateriel";
    const FIND_NUMBER_OF_MATERIAL_WITH_SAME_TYPE_SQL = "SELECT COUNT(*) FROM materiels WHERE idTypeMateriel = :idTypeMateriel";
    const FIND_QUANTITY_OF_MATERIAL_FROM_IDRESERVATION_SQL = "SELECT m.idTypeMateriel, COUNT(rm.idMateriels) AS number_of_materials
        FROM reservations_materiels rm
        JOIN materiels m ON rm.idMateriels = m.idMateriels
        WHERE rm.idReservation = :idReservation
        GROUP BY m.idTypeMateriel";
    const CHECK_MATERIAL_DISPONIBILITY_SQL = "SELECT COUNT(*) 
        FROM materiels ma
        LEFT JOIN reservations_materiels rema ON ma.idMateriels = rema.idMateriels 
        LEFT JOIN reservations re ON rema.idReservation = re.idReservation
        WHERE ma.idTypeMateriel = :idTypeMateriel 
        AND (re.dateFinReservation <= :dateDebut OR re.dateDebutReservation >= :dateFin OR re.idReservation IS NULL)
        AND ma.etat = :etat";
    const FIND_ALL_MATERIAL_AVAILABLE_SQL = "SELECT ma.idMateriels, ma.reference, ma.etat, ma.idTypeMateriel
        FROM materiels ma
        LEFT JOIN reservations_materiels rema ON ma.idMateriels = rema.idMateriels 
        LEFT JOIN reservations re ON rema.idReservation = re.idReservation
        WHERE ma.idTypeMateriel = :idTypeMateriel 
        AND (re.dateFinReservation <= :dateDebut OR re.dateDebutReservation >= :dateFin OR re.idReservation IS NULL)
        AND ma.etat = :etat";
    const UPDATE_SQL = "UPDATE materiels SET reference = :reference, etat = :etat WHERE idMateriels = :idMateriels";
    const DELETE_SQL = "DELETE FROM materiels WHERE idMateriels = :idMateriels";
    const DELETE_LINK_SQL = "DELETE rm FROM reservations_materiels rm JOIN reservations r ON rm.idReservation = r.idReservation WHERE rm.idMateriels = :idMateriels AND r.dateFinReservation < :dateMaintenant";

    public function create(array $data): bool
    {
        try {
            $stmt = $this->db->prepare(self::CREATE_SQL);
            return $stmt->execute([
                ':reference' => $data['reference'],
                ':etat' => $data['etat'],
                ':idTypeMateriel' => $data['idTypeMateriel']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode create de Materiel a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function findById(int $idMateriel): ?Materiel
    {
        try {
            $stmt = $this->db->prepare(self::FIND_BY_ID_SQL);
            $stmt->execute([
                ':idMateriels' => $idMateriel
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $typeMateriel = $this->typeMaterielDAO->findById($result['idTypeMateriel']);
                if ($typeMateriel) {
                    return MaterielFactory::create($result, $typeMateriel);
                }
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findById de Materiel a échoué : ' . $e->getMessage());
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
                $arrayOfMaterielObject = [];
                foreach ($results as $materiel) {
                    $typeMateriel = $this->typeMaterielDAO->findById($materiel['idTypeMateriel']);
                    if (!$typeMateriel) {
                        Log::getLogger()->warning('La méthode findAll de Materiel a échoué à trouver un TypeMateriel d\'un Materiel');
                        continue;
                    }
                    $arrayOfMaterielObject[] = MaterielFactory::create($materiel, $typeMateriel);
                }
                return $arrayOfMaterielObject;
            }

            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findByAll de Materiel a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findAllMaterialWithSameType(int $idTypeMateriel): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_ALL_MATERIAL_WITH_SAM_TYPE_SQL);
            $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                $arrayOfMaterielObject = [];
                foreach ($results as $materiel) {
                    $typeMateriel = $this->typeMaterielDAO->findById($materiel['idTypeMateriel']);
                    if (!$typeMateriel) {
                        Log::getLogger()->warning('La méthode findAllMaterialWithSameType de Materiel a échoué à trouver un TypeMateriel d\'un Materiel');
                        continue;
                    }
                    $arrayOfMaterielObject[] = MaterielFactory::create($materiel, $typeMateriel);
                }
                return $arrayOfMaterielObject;
            }

            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAllMaterialWithSameType de Materiel a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findNumberOfMaterielWithSameType(int $idTypeMateriel): ?int
    {
        try {
            $stmt = $this->db->prepare(self::FIND_NUMBER_OF_MATERIAL_WITH_SAME_TYPE_SQL);
            $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel
            ]);

            $result = $stmt->fetchColumn();

            if ($result) {
                return $result;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findNumberOfMaterielWithSameType de Materiel a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findQuantityOfMaterialFromIdReservation(int $idReservation): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_QUANTITY_OF_MATERIAL_FROM_IDRESERVATION_SQL);
            $stmt->execute([
                ':idReservation' => $idReservation
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                    return $result;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findQuantityOfMaterialFromIdReservation de Materiel a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findAllMaterialAvailable(int $idTypeMateriel, int $dateDebut, int $dateFin): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_ALL_MATERIAL_AVAILABLE_SQL);
            $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel,
                ':dateDebut' => $dateDebut,
                ':dateFin' => $dateFin,
                ':etat' => EtatMateriel::DISPONIBLE->value
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                $arrayOfMaterielObject = [];
                foreach ($results as $materiel) {
                    $typeMateriel = $this->typeMaterielDAO->findById($materiel['idTypeMateriel']);
                    if (!$typeMateriel) {
                        Log::getLogger()->warning('La méthode findAllMaterialAvailable de Materiel a échoué à trouver un TypeMateriel d\'un Materiel');
                        continue;
                    }
                    $arrayOfMaterielObject[] = MaterielFactory::create($materiel, $typeMateriel);
                }
                return $arrayOfMaterielObject;
            }

            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAllMaterialAvailable de MaterielDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function checkMaterialDisponibility(int $idTypeMateriel, int $dateDebut, int $dateFin): ?int
    {
        try {
            $stmt = $this->db->prepare(self::CHECK_MATERIAL_DISPONIBILITY_SQL);
            $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel,
                ':dateDebut' => $dateDebut,
                ':dateFin' => $dateFin,
                ':etat' => EtatMateriel::DISPONIBLE->value
            ]);

            $result = $stmt->fetchColumn();

            if ($result !== false) {
                return (int) $result;
            }

            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode checkMaterialDisponibility de MaterielDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function update(array $data): bool
    {
        try {
            $stmt = $this->db->prepare(self::UPDATE_SQL);
            return $stmt->execute([
                ':reference' => $data['reference'],
                ':etat' => $data['etat'],
                'idMateriels' => $data['idMateriels']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode update de Materiel a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $idMateriel): bool
    {
        try {
            $this->db->beginTransaction();

            $materiel = $this->findById($idMateriel);

            $this->typeMaterielDAO->update([
                'libelle' => $materiel->getTypeMateriel()->getLibelle(),
                'quantite' => $materiel->getTypeMateriel()->getQuantite() - 1,
                'idCategorie' => $materiel->getTypeMateriel()->getCategorie()->getIdCategorie(),
                'idTypeMateriel' => $materiel->getTypeMateriel()->getIdTypeMateriel()
            ]);

            $stmtLink = $this->db->prepare(self::DELETE_LINK_SQL);
            $stmtLink->execute([
                ':idMateriels' => $idMateriel,
                ':dateMaintenant' => time()
            ]);

            $stmt = $this->db->prepare(self::DELETE_SQL);
            $stmt->execute([
                ':idMateriels' => $idMateriel
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            Log::getLogger()->warning('La méthode delete de Materiel a échoué : ' . $e->getMessage());
            return false;
        }
    }
}
