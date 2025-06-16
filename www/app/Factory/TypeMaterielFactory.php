<?php

namespace App\Factory;

use App\Models\Categorie;
use App\Models\TypeMateriel;

class TypeMaterielFactory
{
    public static function create(array $data, Categorie $categorie): TypeMateriel
    {
        return new TypeMateriel(
            $data['idTypeMateriel'],
            $data['libelle'],
            $data['quantite'],
            $categorie
        );
    }
}