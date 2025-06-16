<?php

namespace App\Models\Interfaces;

use App\Models\Utilisateur;

interface UtilisateurDAO
{
    public function create(array $data): bool;
    public function findByEmail(string $email): ?Utilisateur;
    public function findByIdUtilisateur(int $idUtilisateur): ?Utilisateur;
    public function findAll(): ?array;

}