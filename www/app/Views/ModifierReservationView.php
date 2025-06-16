<?php

use Config\Session; ?>
<link rel="stylesheet" href="/public/assets/css/CreationReservation.css">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="mb-0">Modifier ma reservation</h2>
                </div>

                <?php if (Session::getInstance()->isset('error')): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php
                        echo Session::get('error');
                        Session::delete('error');
                        ?>
                    </div>
                <?php endif; ?>

                <div class="form-body">
                    <form action="/modify-reservation" method="POST">
                        <div class="row">
                            <div>
                                <input type="hidden" name="idUtilisateur" value="<?= $reservation->getUtilisateur()->getIdUtilisateur() ?>">
                            </div>
                            <div>
                                <input type="hidden" name="idReservation" value="<?= $reservation->getIdReservation() ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dateDebutReservation" class="form-label required">Date de début</label>
                                <input type="date" class="form-control" id="dateDebutReservation" name="dateDebutReservation" value="<?= date('Y-m-d', $reservation->getDateDebutReservation()) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="heureDebutReservation" class="form-label required">Heure de début</label>
                                <select class="form-select" id="heureDebutReservation" name="heureDebutReservation" required>
                                    <option value="">Choisir une heure</option>
                                    <option value="08:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '08:00' ? "selected" : "" ?>>08:00</option>
                                    <option value="09:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '09:00' ? "selected" : "" ?>>09:00</option>
                                    <option value="10:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '10:00' ? "selected" : "" ?>>10:00</option>
                                    <option value="11:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '11:00' ? "selected" : "" ?>>11:00</option>
                                    <option value="12:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '12:00' ? "selected" : "" ?>>12:00</option>
                                    <option value="13:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '13:00' ? "selected" : "" ?>>13:00</option>
                                    <option value="14:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '14:00' ? "selected" : "" ?>>14:00</option>
                                    <option value="15:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '15:00' ? "selected" : "" ?>>15:00</option>
                                    <option value="16:00" <?= date('H:i',$reservation->getDateDebutReservation()) == '16:00' ? "selected" : "" ?>>16:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dateFinReservation" class="form-label required">Date de fin</label>
                                <input type="date" class="form-control" id="dateFinReservation" name="dateFinReservation" value="<?= date('Y-m-d', $reservation->getDateFinReservation()) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="heureFinReservation" class="form-label required">Heure de fin</label>
                                <select class="form-select" id="heureFinReservation" name="heureFinReservation" required>
                                    <option value="">Choisir une heure</option>
                                    <option value="09:00" <?= date('H:i',$reservation->getDateFinReservation()) == '09:00' ? "selected" : "" ?>>09:00</option>
                                    <option value="10:00" <?= date('H:i',$reservation->getDateFinReservation()) == '10:00' ? "selected" : "" ?>>10:00</option>
                                    <option value="11:00" <?= date('H:i',$reservation->getDateFinReservation()) == '11:00' ? "selected" : "" ?>>11:00</option>
                                    <option value="12:00" <?= date('H:i',$reservation->getDateFinReservation()) == '12:00' ? "selected" : "" ?>>12:00</option>
                                    <option value="13:00" <?= date('H:i',$reservation->getDateFinReservation()) == '13:00' ? "selected" : "" ?>>13:00</option>
                                    <option value="14:00" <?= date('H:i',$reservation->getDateFinReservation()) == '14:00' ? "selected" : "" ?>>14:00</option>
                                    <option value="15:00" <?= date('H:i',$reservation->getDateFinReservation()) == '15:00' ? "selected" : "" ?>>15:00</option>
                                    <option value="16:00" <?= date('H:i',$reservation->getDateFinReservation()) == '16:00' ? "selected" : "" ?>>16:00</option>
                                    <option value="17:00" <?= date('H:i',$reservation->getDateFinReservation()) == '17:00' ? "selected" : "" ?>>17:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="materiel" class="form-label required">Matériel</label>
                                <select class="form-select" id="materiel" name="idTypeMateriel" required>
                                    <?php if ($listTypeMateriel) : ?>
                                        <option value="">Sélectionner un matériel</option>
                                        <?php foreach ($listTypeMateriel as $typeMateriel) : ?>
                                            <option value="<?= $typeMateriel->getIdTypeMateriel() ?> " <?= $idTypeMateriel == $typeMateriel->getIdTypeMateriel() ? "selected" : "" ?>><?= $typeMateriel->getLibelle() ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Aucun matériel disponible</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="quantite" class="form-label required">Quantité</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" min="1" max="99" value="<?= $quantite ?>" required>
                            </div>
                        </div>

                        <div id="disponibility"></div>

                        <div class="mb-3">
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <a href="/gestion-reservation" class="btn btn-outline-secondary me-md-2">
                                    Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>