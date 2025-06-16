<?php

namespace App\Models;

use App\Enums\EtatMateriel;

class Materiel
{
    public function __construct(
        private int $idMateriel,
        private string $reference,
        private EtatMateriel $etatMateriel,
        private TypeMateriel $typeMateriel
    )
    {}

    public function getIdMateriel(): int
    {
        return $this->idMateriel;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getEtatMateriel(): EtatMateriel
    {
        return $this->etatMateriel;
    }

    public function getTypeMateriel(): TypeMateriel
    {
        return $this->typeMateriel;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function setEtatMateriel(EtatMateriel $etatMateriel): void
    {
        $this->etatMateriel = $etatMateriel;
    }

    public function setTypeMateriel(TypeMateriel $typeMateriel): void
    {
        $this->typeMateriel = $typeMateriel;
    }
}