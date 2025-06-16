<?php

use Config\Session; ?>
<link rel="stylesheet" href="/public/assets/css/GestionMateriel.css">
<div class="main-container">
    <!-- Header -->
    <div class="header-section text-center py-4">
        <h1 class="display-4 mb-2"><i class="bi bi-tools"></i> Gestion du Matériel</h1>
        <p class="lead mb-0">Système de gestion et suivi du matériel</p>
    </div>

    <div class="d-flex justify-content-end m-3">
        <a href="/dashboard" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

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

    <div class="container-fluid p-4">
        <!-- Gestion des catégories -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-tags"></i> Gestion des Catégories</h5>
            </div>
            <div class="card-body">
                <form action="/gestion-materiel/create-categorie" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="newCategory" class="visually-hidden">Nom de la nouvelle catégorie</label>
                            <input name="libelle" type="text" id="newCategory" class="form-control" placeholder="Nom de la nouvelle catégorie" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle"></i> Ajouter Catégorie
                            </button>
                        </div>
                    </div>
                </form>
                <div class="mt-3">
                    <strong>Catégories existantes : (Appuyez sur une catégorie pour la modifier)</strong>
                    <div id="categoriesList" class="mt-2">
                        <?php if ($categories) : ?>
                            <?php foreach ($categories as $categorie) : ?>
                                <a class="text-decoration-none" href="">
                                    <span class="badge bg-secondary me-2 mb-2"><?= $categorie->getLibelle() ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Aucune catégorie trouvé</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestion des types -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Gestion des Types de Matériel</h5>
            </div>
            <div class="card-body">
                <form action="/gestion-materiel/create-type-materiel" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="idCategorie" id="categoryForType" class="form-select">
                                <?php if ($categories) : ?>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php foreach ($categories as $categorie) : ?>
                                        <option value="<?= $categorie->getIdCategorie() ?>"><?= $categorie->getLibelle() ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">Aucune catégorie trouvé</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input name="libelle" type="text" id="newType" class="form-control" placeholder="Nom du nouveau type">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-plus-circle"></i> Ajouter Type
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 my-3">
                            <label for="quantite">Quantité :</label>
                            <input type="number" id="quantite" name="quantite" min="1" max="99" value="1" required>
                        </div>
                    </div>
                </form>
                <div class="mt-3">
                    <strong>Types existants :</strong>
                    <div id="typesList" class="mt-2">
                        <?php if ($typeMateriels) : ?>
                            <?php foreach ($typeMateriels as $typeMateriel) : ?>
                                <a class="text-decoration-none" href="">
                                    <span class="badge bg-info me-2 mb-2"><?= $typeMateriel->getLibelle() ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Aucun type matériel trouvé</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ajout/Modification de matériel -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-plus-square"></i> Enregistrer le matériel</h5>
            </div>
            <div class="card-body">
                <form id="materialForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="materialType" class="form-label">Type de matériel</label>
                                <select name="" id="materialType" class="form-select" required>
                                    <?php if ($typeMateriels) : ?>
                                        <option value="">Sélectionner un type</option>
                                        <?php foreach ($typeMateriels as $typeMateriel) : ?>
                                            <option value="<?= $typeMateriel->getIdTypeMateriel() ?>"><?= $typeMateriel->getLibelle() ?></option></span>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p>Aucun type matériel trouvé</p>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="cardsContainer" class="row"></div>
            </div>
        </div>

        <!-- Filtre et liste du matériel -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Liste du Matériel</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="filterType" class="form-label">Filtrer par type :</label>
                        <select id="filterType" class="form-select">
                            <?php if ($typeMateriels) : ?>
                                <option value="">Sélectionner un type</option>
                                <?php foreach ($typeMateriels as $typeMateriel) : ?>
                                    <option value="<?= $typeMateriel->getIdTypeMateriel() ?>"><?= $typeMateriel->getLibelle() ?></option></span>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>Aucun type matériel trouvé</p>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <a href=""></a>
                <div id="materialsList" class="row"></div>
            </div>
        </div>
    </div>
</div>
<script src="/public/assets/js/GestionMateriel.js"></script>