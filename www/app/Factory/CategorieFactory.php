<?php

namespace App\Factory;

use App\Models\Categorie;

class CategorieFactory
{
    public static function create(array $data): Categorie
    {
        return new Categorie(
            $data['idCategorie'],
            $data['libelle']
        );
    }
}