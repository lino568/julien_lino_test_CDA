<?php

namespace App\Enums;

enum StatutReservation: string
{
    case EN_COURS = 'en_cours';
    case ANNULE = 'annulé';

    public static function getEnumFromValue(string $value): ?self
    {
        foreach(self::cases() as $case) {
            if ($value === $case->value) {
                return $case;
            }
        }
        return null;
    }

    public static function getArrayOfEnumValue(): array
    {
        $arrayOfEnumValue = [];
        foreach (self::cases() as $case) {
            $arrayOfEnumValue[] = $case->value;
        }
        return $arrayOfEnumValue;
    }
}