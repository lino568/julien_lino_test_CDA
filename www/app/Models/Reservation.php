<?php

namespace App\Models;

use App\Enums\StatutReservation;

class Reservation
{
    public function __construct(
        private int $idReservation,
        private int $dateDebutReservation,
        private int $dateFinReservation,
        private StatutReservation $statutReservation,
        private Utilisateur $utilisateur
    )
    {}

    public function getIdReservation(): int
    {
        return $this->idReservation;
    }

    public function getDateDebutReservation(): int
    {
        return $this->dateDebutReservation;
    }

    public function getDateFinReservation(): int
    {
        return $this->dateFinReservation;
    }

    public function getStatutReservation(): StatutReservation
    {
        return $this->statutReservation;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setDateDebutReservation(int $dateDebutReservation): void
    {
        $this->dateDebutReservation = $dateDebutReservation;
    }

    public function setDateFinReservation(int $dateFinReservation): void
    {
        $this->dateFinReservation = $dateFinReservation;
    }

    public function setStatutReservation(StatutReservation $statutReservation): void
    {
        $this->statutReservation = $statutReservation;
    }

    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }
}