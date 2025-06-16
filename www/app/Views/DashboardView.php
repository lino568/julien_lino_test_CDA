<?php

use Config\Session;
use App\Enums\Role; ?>
<!-- Header -->
<link rel="stylesheet" href="/public/assets/css/Dashboard.css">
<header class="header-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header-content text-center position-relative p-3">
                    <h1 class="mb-0">
                        <i class="fas fa-calendar-alt me-3"></i>
                        Calendrier des Réservations
                    </h1>
                    <p class="mt-2 mb-0 opacity-75">Gestion du matériel pédagogique - GRETA</p>

                    <a href="/deconnexion" class="btn btn-outline-danger btn-sm position-absolute top-50 end-0 translate-middle-y me-3">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="d-none d-md-inline ms-2">Déconnexion</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

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

<div class="container mt-4">
    <?php if ($user->getRole() === Role::ENSEIGNANT) : ?>
        <div class="reservation-actions card shadow-sm p-4 mb-4">
            <div class="row justify-content-center text-center">
                <div class="col-md-6 col-lg-4 mb-3 mb-md-0">
                    <a href="/reserver-materiel" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-plus-circle me-2"></i> Réserver du matériel
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="/gestion-reservation" class="btn btn-outline-secondary btn-lg w-100">
                        <i class="fas fa-list-alt me-2"></i> Gérer mes réservations
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($user->getRole() === Role::ADMINISTRATEUR) : ?>
        <div class="materiel-actions card shadow-sm p-4 mb-4">
            <div class="row justify-content-center text-center">
                <div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    <a href="/gestion-materiel" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-cogs me-2"></i> Gestion du matériel
                    </a>
                </div>
                <div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    <a href="/gestion-utilisateur" class="btn btn-outline-secondary btn-lg w-100">
                        <i class="fas fa-users-cog me-2"></i> Gestion des utilisateurs
                    </a>
                </div>
                <div class="col-md-12 col-lg-4">
                    <a href="/gestion-reservation" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-calendar-alt me-2"></i> Les réservations
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sélecteur de matériel -->
    <div class="material-selector">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label for="materialSelect" class="form-label fw-bold">
                    <i class="fas fa-laptop me-2 text-muted"></i>Matériel :
                </label>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="materialSelect">
                    <?php if ($typeMateriels) : ?>
                        <option value="">Sélectionner un matériel</option>
                        <?php foreach ($typeMateriels as $typeMateriel) : ?>
                            <?php if ($typeMateriel['nbMateriels'] && $typeMateriel['nbMateriels'] != 0) : ?>
                                <option value="<?= $typeMateriel['idTypeMateriel'] ?>"><?= $typeMateriel['libelle'] ?> (<?= $typeMateriel['nbMateriels'] ?> disponibles)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value="">Aucun matériel trouvé</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="loadReservations()">
                    <i class="fas fa-search me-2"></i>Afficher
                </button>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="calendar-container">
        <div class="calendar-header">
            <h3 class="mb-0">
                <i class="fas fa-calendar-week me-2"></i>
                Semaine du <span id="weekStart"></span> au <span id="weekEnd"></span>
            </h3>
            <div class="week-navigation">
                <button class="nav-btn" onclick="previousWeek()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-outline-primary" onclick="goToCurrentWeek()">
                    Aujourd'hui
                </button>
                <button class="nav-btn" onclick="nextWeek()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div id="calendarContent">
            <div class="empty-state">
                <i class="fas fa-laptop"></i>
                <h4>Sélectionnez un matériel</h4>
                <p>Choisissez un équipement dans la liste ci-dessus pour voir ses réservations.</p>
            </div>
        </div>
    </div>
</div>
<script src="/public/assets/js/Dashboard.js"></script>