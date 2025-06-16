<?php

namespace App\Models\Interfaces;

use App\Models\Materiel;

interface MaterielDAO
{
    public function create(array $data): bool;

    public function findById(int $idMateriel): ?Materiel;

    public function findAll(): ?array;

    public function findAllMaterialWithSameType(int $idTypeMateriel): ?array;

    public function findNumberOfMaterielWithSameType(int $idTypeMateriel): ?int;

    public function findQuantityOfMaterialFromIdReservation(int $idReservation): ?array;

    public function checkMaterialDisponibility(int $idTypeMateriel, int $dateDebut, int $dateFin): ?int;
    
    public function findAllMaterialAvailable(int $idTypeMateriel, int $dateDebut, int $dateFin): ?array;

    public function update(array $data): bool;

    public function delete(int $idMateriel): bool;

}