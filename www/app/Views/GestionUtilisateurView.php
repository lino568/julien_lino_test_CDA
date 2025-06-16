<?php

use Config\Session; ?>
<link rel="stylesheet" href="assets/css/GestionUtilisateur.css">
<div class="container py-4">
    <!-- En-tête avec titre et boutons -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h2 mb-0">Gestion des Utilisateurs</h1>
        <div class="btn-group-mobile">
            <a class="btn btn-danger" href="/dashboard">
                <i class="bi bi-arrow-left me-2"></i>
                Retour
            </a>
            <a class="btn btn-success" href="/gestion-utilisateur/nouvel-utilisateur">
                <i class="bi bi-person-plus me-2"></i>
                Créer un utilisateur
            </a>
        </div>
    </div>

    <!--  Messages d'alerte -->
    <?php if (Session::getInstance()->isset('error')): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php
            echo Session::get('error');
            Session::delete('error');
            ?>
        </div>
    <?php endif; ?>
    <?php if (Session::getInstance()->isset('success')): ?>
        <div class="alert alert-success text-center" role="alert">
            <?php
            echo Session::get('success');
            Session::delete('success');
            ?>
        </div>
    <?php endif; ?>

    <!-- Tableau des utilisateurs -->
    <div class="card">
        <div class="card-body">
            <?php if ($utilisateurs) : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Rôle</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($utilisateurs as $user) : ?>
                                <tr>
                                    <td><?= $user->getNom() ?></td>
                                    <td><?= $user->getPrenom() ?></td>
                                    <td><?= $user->getEmail() ?></td>
                                    <td><?= $user->getRole()->value ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a class="btn btn-primary" href="/gestion-utilisateur/modifier/<?= $user->getIdUtilisateur() ?>">
                                                <i class="bi bi-pencil me-1"></i>
                                                <span class="d-none d-sm-inline">Modifier</span>
                                            </a>
                                            <a class="btn btn-danger" href="/gestion-utilisateur/supprimer/<?= $user->getIdUtilisateur() ?>">
                                                <i class="bi bi-trash me-1"></i>
                                                <span class="d-none d-sm-inline">Supprimer</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>

                <div class="text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="bi bi-people" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                    <p class="text-muted">Commencez par créer votre premier utilisateur</p>
                    <a class="btn btn-success" href="/gestion-utilisateur/nouvel-utilisateur">
                        <i class="bi bi-person-plus me-2"></i>
                        Créer un utilisateur
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>