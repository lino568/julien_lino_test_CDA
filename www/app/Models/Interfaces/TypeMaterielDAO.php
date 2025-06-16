<?php

namespace App\Models\Interfaces;

use App\Models\TypeMateriel;

interface TypeMaterielDAO
{
    public function create(array $data): bool;

    public function findById(int $idTypeMateriel): ?TypeMateriel;

    public function findAll(): ?array;

    public function findAllWithAvailabeMaterial(): ?array;

    public function update(array $data): bool;

    public function delete(int $idTypeMateriel): bool;

}