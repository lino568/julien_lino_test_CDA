 <?php

    use Config\Session; ?>
 <link rel="stylesheet" href="/public/assets/css/CreationUtilisateur.css">
 <div class="container-fluid main-container">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-12 col-md-8 col-lg-6">
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
                 <!-- Carte principale -->
                 <div class="card">
                     <div class="card-header text-center">
                         <h1 class="mb-0">
                             <i class="bi bi-person-plus-fill me-3"></i>
                             Créer un nouvel utilisateur
                         </h1>
                     </div>
                     <div class="card-body">
                         <form action="/gestion-utilisateur/creation-utilisateur" method="POST">
                             <!-- Nom -->
                             <div class="form-floating mb-4">
                                 <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                                 <label for="nom">
                                     <i class="bi bi-person me-2"></i>Nom
                                 </label>
                             </div>
                             <!-- Prénom -->
                             <div class="form-floating mb-4">
                                 <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
                                 <label for="prenom">
                                     <i class="bi bi-person me-2"></i>Prénom
                                 </label>
                             </div>
                             <!-- Email -->
                             <div class="form-floating mb-4">
                                 <input type="email" class="form-control" id="email" name="email" placeholder="nom@exemple.com" required>
                                 <label for="email">
                                     <i class="bi bi-envelope me-2"></i>Adresse email
                                 </label>
                             </div>
                             <div class="form-floating mb-4">
                                 <input type="password" class="form-control" id="motDePasse" name="motDePasse" placeholder="Mot de passe" required>
                                 <label for="motDePasse">
                                     <i class="bi bi-person me-2"></i>Mot de passe
                                 </label>
                             </div>
                             <!-- Rôle -->
                             <div class="form-floating mb-4">
                                 <select class="form-select" id="role" name="role" required>
                                     <?php if ($listRole) : ?>
                                         <option value="" selected disabled>Choisissez un rôle</option>
                                         <?php foreach ($listRole as $role) : ?>
                                             <option value="<?= $role ?>"><?= $role ?></option>
                                         <?php endforeach; ?>
                                     <?php else : ?>
                                         <option value="" selected disabled>Aucun rôle trouvé</option>
                                     <?php endif; ?>
                                 </select>
                                 <label for="role">
                                     <i class="bi bi-person-badge me-2"></i>Rôle utilisateur
                                 </label>
                                 <div class="invalid-feedback">
                                     Veuillez sélectionner un rôle.
                                 </div>
                             </div>

                             <!-- Liens d'action -->
                             <div class="d-grid gap-3 d-md-flex justify-content-md-end pt-3">
                                 <a href="/gestion-utilisateur"
                                     class="btn btn-outline-secondary col-12 col-md-auto">
                                     <i class="bi bi-arrow-left me-2"></i>
                                     Annuler
                                 </a>
                                 <button type="submit" class="btn btn-primary col-12 col-md-auto">
                                     <i class="bi bi-check-lg me-2"></i>
                                     Enregistrer
                                 </button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>