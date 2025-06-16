<?php

namespace App\Models\Interfaces;

use App\Models\Categorie;

interface CategorieDAO
{
    public function create(string $libelle): bool;

    public function findById(int $idCategorie): ?Categorie;

    public function findAll(): ?array;

    public function update(array $data): bool;

    public function delete(int $idCategorie): bool;
}