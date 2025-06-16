<?php

namespace App\Models;

class TypeMateriel
{
    public function __construct(
        private int $idTypeMateriel,
        private string $libelle,
        private int $quantite,
        private Categorie $categorie
    )
    {}

    public function getIdTypeMateriel(): int
    {
        return $this->idTypeMateriel;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function setCategorie(Categorie $categorie): void
    {
        $this->categorie = $categorie;
    }
}