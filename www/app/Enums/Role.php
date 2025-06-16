<?php

namespace App\Enums;

enum Role: string
{
    case ADMINISTRATEUR = "Administrateur";
    case ENSEIGNANT = "Enseignant";

    public static function getEnumFromValue(string $role): ?Role 
    {
        foreach (self::cases() as $case) {
            if ($role === $case->value) {
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