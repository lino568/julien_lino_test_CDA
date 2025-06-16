<?php

namespace App\Factory;

use Config\Log;
use App\Models\Materiel;
use App\Enums\EtatMateriel;
use App\Models\TypeMateriel;

class MaterielFactory
{
    public static function create(array $data, TypeMateriel $typeMateriel): ?Materiel
    {
        $enumEtatFromValue = EtatMateriel::getEnumFromValue($data['etat']);

        if (!$enumEtatFromValue) {
            Log::getLogger()->warning('La valeur du rôle retournée depuis la BDD ne correspond pas à un rôle valide dans MaterielFactory.');
            return null;
        }

        return new Materiel(
            $data['idMateriels'],
            $data['reference'],
            $enumEtatFromValue,
            $typeMateriel
        );
    }
}