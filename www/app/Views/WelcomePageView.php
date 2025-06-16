<!-- Hero Section -->
 <link rel="stylesheet" href="assets/css/WelcomePage.css">
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center hero-content">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-graduation-cap me-3"></i>GRETA
                    </div>
                    <p class="subtitle">Groupement d'Établissements</p>
                </div>
                <h1 class="display-4 fw-bold mb-4">Gestion de Matériel Pédagogique</h1>
                <p class="lead mb-5">Plateforme dédiée à la réservation et à la gestion du matériel éducatif</p>
                <button type="button" class="btn btn-login btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
 
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center h-100">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Professeurs</h4>
                    <p class="text-muted">Réservez facilement le matériel pédagogique nécessaire pour vos cours et formations.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center h-100">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Administrateurs</h4>
                    <p class="text-muted">Gérez les réservations, supervisez l'utilisation du matériel et optimisez les ressources.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="feature-card text-center h-100">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Matériel</h4>
                    <p class="text-muted">Ordinateurs, projecteurs, tablettes et tout l'équipement nécessaire à la formation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de connexion -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="fas fa-user-circle me-2"></i>Connexion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="loginForm" method="POST" action="/connexion">
                    <!-- Zone d'affichage des erreurs -->
                    <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorText"></span>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope me-2 text-muted"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="motDePasse" class="form-label fw-bold">
                            <i class="fas fa-lock me-2 text-muted"></i>Mot de passe
                        </label>
                        <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Contactez l'administrateur système en cas de problème de connexion
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>GRETA
                </h6>
                <p class="mb-0">Plateforme de gestion de matériel pédagogique</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    © 2025 - Tous droits réservés
                </p>
            </div>
        </div>
    </div>
</footer>
<script src="assets/js/WelcomePage.js"></script>