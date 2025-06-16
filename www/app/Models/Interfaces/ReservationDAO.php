<?php

namespace App\Models\Interfaces;

use App\Models\Reservation;

interface ReservationDAO
{
    public function create(int $dateDebut, int $dateFin, int $idUtilisateur, int $idTypeMateriel, int $quantite): bool;

    public function createLink(int $idReservation, array $ArrayAvailableMateriels, int $quantite): bool;

    public function findByIdReservation(int $idReservation): ?Reservation;

    public function findAll(): ?array;

    public function findAllByIdUtilisateur(int $idUtilisateur): ?array;

    public function findAllByIdTypeMateriel(int $idTypeMateriel): ?array;

    public function update(array $data): bool;

    public function delete(int $idReservation): bool;

    public function deleteLink(int $idReservation): bool;

}