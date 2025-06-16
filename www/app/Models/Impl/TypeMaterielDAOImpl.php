<?php

namespace App\Models\Impl;

use PDO;
use Config\Log;
use PDOException;
use Config\Database;
use App\Models\TypeMateriel;
use App\Factory\TypeMaterielFactory;
use App\Models\Interfaces\CategorieDAO;
use App\Models\Interfaces\TypeMaterielDAO;

class TypeMaterielDAOImpl implements TypeMaterielDAO
{
    private PDO $db;
    private CategorieDAO $categorieDAO;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->categorieDAO = new CategorieDAOImpl();
    }

    const CREATE_SQL = "INSERT INTO typeMateriels (libelle, quantite, idCategorie) VALUES (:libelle, :quantite, :idCategorie)";
    const FIND_BY_ID_SQL = "SELECT * FROM typeMateriels WHERE idTypeMateriel = :idTypeMateriel";
    const FIND_ALL_SQL = "SELECT * FROM typeMateriels";
    const FIND_ALL_WITH_AVAILABLE_MATERIAL_SQL = "SELECT tm.idTypeMateriel, tm.libelle, tm.idCategorie, COUNT(ma.idMateriels) AS nbMateriels
        FROM typeMateriels tm
        JOIN materiels ma ON tm.idTypeMateriel = ma.idTypeMateriel
        GROUP BY tm.idTypeMateriel, tm.libelle, tm.idCategorie";
    const UPDATE_SQL = "UPDATE typeMateriels SET libelle = :libelle, quantite = :quantite, idCategorie = :idCategorie WHERE idTypeMateriel = :idTypeMateriel";
    const DELETE_SQL = "DELETE FROM typeMateriels WHERE idTypeMateriel = :idTypeMateriel";

    public function create(array $data): bool
    {
        try {
            $stmt = $this->db->prepare(self::CREATE_SQL);
            return $stmt->execute([
                ':libelle' => $data['libelle'],
                ':quantite' => $data['quantite'],
                ':idCategorie' => $data['idCategorie']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode create de TypeMateriel a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function findById(int $idTypeMateriel): ?TypeMateriel
    {
        try {
            $stmt = $this->db->prepare(self::FIND_BY_ID_SQL);
            $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $categorie = $this->categorieDAO->findById($result['idCategorie']);

                if ($categorie) {
                    return TypeMaterielFactory::create($result, $categorie);
                }
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findById de TypeMateriel a échoué : ' . $e->getMessage());
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
                $arrayOfTypeMaterielObject = [];
                foreach ($results as $TypeMateriel) {
                    $categorie = $this->categorieDAO->findById($TypeMateriel['idCategorie']);
                    if (!$categorie) {
                        Log::getLogger()->warning('La méthode findAll de TypeMateriel a échoué à trouver une catégorie d\'un TypeMateriel');
                        continue;
                    }
                    $arrayOfTypeMaterielObject[] = TypeMaterielFactory::create($TypeMateriel, $categorie);
                }
                return $arrayOfTypeMaterielObject;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAll de TypeMateriel a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findAllWithAvailabeMaterial(): ?array
    {
        try {
            $stmt = $this->db->prepare(self::FIND_ALL_WITH_AVAILABLE_MATERIAL_SQL);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                $arrayOfTypeMateriel = [];
                foreach ($results as $TypeMateriel) {
                    $arrayOfTypeMateriel[] = $TypeMateriel;
                }
                return $arrayOfTypeMateriel;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAllWithAvailabeMaterial de TypeMateriel a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function update(array $data): bool
    {
        try {
            $stmt = $this->db->prepare(self::UPDATE_SQL);
            return $stmt->execute([
                ':libelle' => $data['libelle'],
                ':quantite' => $data['quantite'],
                ':idCategorie' => $data['idCategorie'],
                ':idTypeMateriel' => $data['idTypeMateriel']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode update de TypeMateriel a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $idTypeMateriel): bool
    {
        try {
            $stmt = $this->db->prepare(self::DELETE_SQL);
            return $stmt->execute([
                ':idTypeMateriel' => $idTypeMateriel
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode delete de TypeMateriel a échoué : ' . $e->getMessage());
            return false;
        }
    }
}
