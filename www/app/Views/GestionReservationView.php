<?php

use App\Enums\Role;
use Config\Session; ?>
<link rel="stylesheet" href="assets/css/GestionReservation.css">
<!-- Header -->
<div class="header-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="reservation-icon">
                            <i class="bi bi-tools fs-5"></i>
                        </div>
                        <div>
                            <h1 class="mb-0 h3">Gestion des Réservations de Matériel</h1>
                            <p class="mb-0 opacity-75">Gérez vos emprunts de matériel pédagogique</p>
                        </div>
                    </div>
                    <a href="/dashboard" class="btn btn-danger btn-sm">Retour</a>
                </div>
            </div>
        </div>
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

<div class="container">
    <!-- Liste des réservations -->
    <div class="row">
        <?php if ($reservations) : ?>
            <!-- Réservation 1 - Plus récente -->
            <?php foreach ($reservations as $reservation) : ?>
                <div class="col-12 col-lg-6">
                    <div class="card reservation-card <?= $reservation['dateFinReservation'] < time() ? "past-reservation" : "" ?>">
                        <div class="card-header-custom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0"><?= $user->getRole() === Role::ADMINISTRATEUR ? $reservation['prenom'] . " " . $reservation['nom'] . " - " : "" ?><?= $reservation['libelle'] ?></h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Emprunt</small>
                                    <strong><?= date('d/m/Y', $reservation['dateDebutReservation']) ?></strong>
                                    <div class="text-primary"><?= date('H:i', $reservation['dateDebutReservation']) ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Retour</small>
                                    <strong><?= date('d/m/Y', $reservation['dateFinReservation']) ?></strong>
                                    <div class="text-primary"><?= date('H:i', $reservation['dateFinReservation']) ?></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <small class="text-muted d-block">Quantité</small>
                                    <strong><?= $reservation['number_of_materials'] ?></strong>
                                </div>
                            </div>
                            <?php if ($user->getRole() !== Role::ADMINISTRATEUR) : ?>
                                <div class="d-flex gap-2">
                                    <a href="/gestion-reservation/modifier/<?= $reservation['idReservation'] ?>" class="btn btn-modify btn-action flex-fill <?= (time() + (60 * 60 * 24)) >  $reservation['dateDebutReservation'] ? "disabled-link" : "" ?>">
                                        <i class="bi bi-pencil me-1"></i>Modifier
                                    </a>
                                    <a href="/gestion-reservation/delete/<?= $reservation['idReservation'] ?>"
                                        class="btn btn-cancel btn-action flex-fill <?= (time() + (60 * 60 * 24)) >  $reservation['dateDebutReservation'] ? "disabled-link" : "" ?>"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                                        <i class="bi bi-x-circle me-1"></i>Annuler
                                    </a>

                                </div>
                                <?php if (time() < $reservation['dateFinReservation']) : ?>
                                    <div class="warning-text">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Annulation et modification impossible à moins de 24h de la réservation
                                    </div>
                                <?php else : ?>
                                    <small class="text-muted">Réservation terminée</small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-center">
                <h3>Aucune réservation trouvée</h3>
            </div>
        <?php endif; ?>
    </div>
</div>