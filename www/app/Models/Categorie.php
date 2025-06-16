<?php

namespace App\Models;

class Categorie
{
    
    public function __construct(
        private int $idCategorie,
        private string $libelle
    )
    {}

    public function getIdCategorie(): int
    {
        return $this->idCategorie;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }
}