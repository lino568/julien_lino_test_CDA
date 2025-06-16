<?php

namespace App\Models\Impl;

use PDO;
use Config\Log;
use PDOException;
use Config\Database;
use App\Models\Utilisateur;
use App\Factory\UtilisateurFacory;
use App\Models\Interfaces\UtilisateurDAO;

class UtilisateurDAOImpl implements UtilisateurDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    const CREATE_SQL = "INSERT INTO Utilisateurs (nom, prenom, email, motDePasse, role) VALUES (:nom, :prenom, :email, :motDePasse, :role)";
    const FIND_BY_EMAIL_SQL = "SELECT * FROM Utilisateurs WHERE email = :email LIMIT 1";
    const FIND_BY_ID_UTILISATEUR_SQL = "SELECT * FROM Utilisateurs WHERE idUtilisateur = :idUtilisateur";
    const FIND_ALL_SQL = "SELECT * FROM Utilisateurs";


    public function create(array $data): bool
    {
        $hashedPassword = password_hash($data['motDePasse'], PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare(self::CREATE_SQL);
            return $stmt->execute([
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':email' => $data['email'],
                ':motDePasse' => $hashedPassword,
                ':role' => $data['role']
            ]);
        } catch (PDOException $e) {
            Log::getLogger()->info('Problème lors de l\'insertion d\'un utilisateur en BDD' . $e->getMessage());
            return false;
        }
    }


    public function findByEmail(string $email): ?Utilisateur
    {
        try {
            $stmt = $this->db->prepare(self::FIND_BY_EMAIL_SQL);
            $stmt->execute([
                ':email' => $email
            ]);

            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur) {
                return UtilisateurFacory::create($utilisateur);
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->info('La méthode findByEmail de UtilisateurDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }

    public function findByIdUtilisateur(int $idUtilisateur): ?Utilisateur
    {
        try {
            $stmt = $this->db->prepare(self::FIND_BY_ID_UTILISATEUR_SQL);
            $stmt->execute([
                ':idUtilisateur' => $idUtilisateur
            ]);

            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilisateur) {
                return UtilisateurFacory::create($utilisateur);
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->info('La méthode findByIdUtilisateur de UtilisateurDAOImpl a échoué : ' . $e->getMessage());
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
                $arrayOfUserObject = [];
                foreach ($results as $user) {
                    $arrayOfUserObject[] = UtilisateurFacory::create($user);
                }
                return $arrayOfUserObject;
            }
            return null;
        } catch (PDOException $e) {
            Log::getLogger()->info('La méthode findAll de UtilisateurDAOImpl a échoué : ' . $e->getMessage());
            return null;
        }
    }
}
