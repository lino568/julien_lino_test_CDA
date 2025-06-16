<?php

namespace App\Enums;

enum EtatMateriel: string
{
    case DISPONIBLE = 'disponible';
    case EN_MAINTENANCE = 'en_maintenance';
    case HORS_SERVICE = 'hors_service';

    public static function getEnumFromValue(string $value): ?self
    {
        foreach (self::cases() as $case){
            if ($value === $case->value){
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