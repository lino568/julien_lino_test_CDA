<?php

namespace App\Factory;

use Config\Log;
use App\Models\Reservation;
use App\Models\Utilisateur;
use App\Enums\StatutReservation;

class ReservationFactory
{
    public static function create(array $data, Utilisateur $utilisateur): ?Reservation
    {
        $enumStatutReservationFromValue = StatutReservation::getEnumFromValue($data['statut']);

        if (!$enumStatutReservationFromValue) {
            Log::getLogger()->warning('La valeur du statutReservation retournée depuis la BDD ne correspond pas à un statut valide dans ReservationFactory.');
            return null;
        }

        return new Reservation(
            $data['idReservation'],
            $data['dateDebutReservation'],
            $data['dateFinReservation'],
            $enumStatutReservationFromValue,
            $utilisateur
        );
    }
}