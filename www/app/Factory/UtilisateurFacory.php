<?php

namespace App\Factory;

use Config\Log;
use App\Enums\Role;
use App\Models\Utilisateur;

class UtilisateurFacory
{
    public static function create(array $data): ?Utilisateur
    {
        $enumRoleFromValueInDatabase = Role::getEnumFromValue($data['role']);

        if (!$enumRoleFromValueInDatabase) {
            Log::getLogger()->warning('La valeur du rôle retournée depuis la BDD ne correspond pas à un rôle valide dans UtilisateurFactory.');
            return null;
        }

        return new Utilisateur(
            $data['idUtilisateur'],
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['motDePasse'],
            $enumRoleFromValueInDatabase
        );
    }
}