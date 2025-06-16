<?php

namespace App\Dtos;

use App\Enums\Role;
use App\Models\Utilisateur;

class UtilisateurDto
{
    private int $idUtilisateur;
    private string $nom;
    private string $prenom;
    private Role $role;

    public function __construct(Utilisateur $utilisateur)
    {
        $this->idUtilisateur = $utilisateur->getIdUtilisateur();
        $this->nom = $utilisateur->getNom();
        $this->prenom = $utilisateur->getPrenom();
        $this->role = $utilisateur->getRole();
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }
}
