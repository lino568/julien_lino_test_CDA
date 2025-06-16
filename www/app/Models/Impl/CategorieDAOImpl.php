<?php

namespace App\Models\Impl;

use PDO;
use Config\Log;
use PDOException;
use Config\Database;
use App\Models\Categorie;
use App\Factory\CategorieFactory;
use App\Models\Interfaces\CategorieDAO;

class CategorieDAOImpl implements CategorieDAO
{

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    const CREATE_SQL = "INSERT INTO categories (libelle) VALUES (:libelle)";
    const FIND_BY_ID_SQL = "SELECT * FROM categories WHERE idCategorie = :idCategorie";
    const FIND_ALL_SQL = "SELECT * FROM categories";
    const UPDATE_SQL = "UPDATE categories SET libelle = :libelle WHERE idCategorie = :idCategorie";
    const DELETE_SQL = "DELETE FROM categories WHERE idCategorie = :idCategorie";

    public function create(string $libelle): bool
    {
        try {
            $stmt = $this->db->prepare(self::CREATE_SQL);
            return $stmt->execute([
                ':libelle' => $libelle
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('Problème lors de l\'insertion d\'une catégorie en BDD : ' . $e->getMessage());
            return false;
        }
    }

    public function findById(int $idCategorie): ?Categorie
    {
        try {
            $stmt = $this->db->prepare(self::FIND_BY_ID_SQL);
            $stmt->execute([
                ':idCategorie' => $idCategorie
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return CategorieFactory::create($result);
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findById de Categorie a échoué : ' . $e->getMessage());
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
                $arrayOfCategorieObject = [];

                foreach ($results as $categorie) {
                    $arrayOfCategorieObject[] = CategorieFactory::create($categorie);
                }
                return $arrayOfCategorieObject;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode findAll de Categorie a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function update(array $data): bool
    {
        try {
            $stmt = $this->db->prepare(self::UPDATE_SQL);
            return $stmt->execute([
                ':libelle' => $data['libelle'],
                ':idCategorie' => $data['idCategorie']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode update de Categorie a échoué : ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $idCategorie): bool
    {
        try {
            $stmt = $this->db->prepare(self::DELETE_SQL);
            return $stmt->execute([
                ':idCategorie' => $idCategorie
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->warning('La méthode delete de Categorie a échoué : ' . $e->getMessage());
            return false;
        }
    }
}
