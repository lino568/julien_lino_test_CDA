<?php

use Config\Session; ?>
<link rel="stylesheet" href="/public/assets/css/PageMateriel.css">
<div class="main-container">
    <div class="card">
        <div class="card-header text-center">
            <h1><i class="bi bi-gear me-2"></i>Détails du Matériel</h1>
            <p class="subtitle mb-0">Gestion des informations matériel</p>
        </div>

        <?php if (Session::getInstance()->isset('error')): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php
                echo Session::get('error');
                Session::delete('error');
                ?>
            </div>
        <?php endif; ?>

        <div class="card-body">
            <form action="/gestion-materiel/materiel/modifier" method="POST">
                <div class="row g-4">
                    <input type="hidden" name="idMateriels" value="<?= $materiel->getIdMateriel() ?>">
                    <div class="col-12">
                        <div class="form-floating">
                            <input name="reference" type="text" class="form-control" id="reference" placeholder="Référence du matériel" value="<?= $materiel->getReference() ?>" required>
                            <label for="reference">
                                <i class="bi bi-tag me-1"></i>Référence
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <select name="etat" class="form-select" id="etat">
                                <?php foreach ($listeEtat as $etat) : ?>
                                    <option value="<?= $etat ?>" <?= $materiel->getEtatMateriel()->value === $etat ? "selected" : "" ?>><?= $etat ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="etat">
                                <i class="bi bi-clipboard-check me-1"></i>État
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end button-group mt-4">
                    <a href="javascript:history.back()" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Retour
                    </a>
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </button>
                    <a href="/gestion-materiel/materiel/supprimer/<?= $materiel->getIdMateriel() ?>" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>