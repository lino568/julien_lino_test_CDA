document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.feature-card');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Focus automatique sur le champ email à l'ouverture du modal
    const loginModal = document.getElementById('loginModal');
    loginModal.addEventListener('shown.bs.modal', function () {
        document.getElementById('email').focus();
    });

    // Gestion du formulaire de connexion avec fetch
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // --- Récupération des valeurs des champs du formulaire ---
        const emailInput = document.getElementById('email'); // Assurez-vous que votre input email a id="email"
        const motDePasseInput = document.getElementById('motDePasse'); // Assurez-vous que votre input mot de passe a id="motDePasse"

        const email = emailInput.value;
        const motDePasse = motDePasseInput.value;
        // --- Fin de la récupération des valeurs ---

        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');

        // Cacher le message d'erreur précédent
        errorMessage.classList.add('d-none');

        // Désactiver le bouton pendant la requête
        const submitBtn = loginForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion...';
        submitBtn.disabled = true;

        fetch('/connexion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Indique au serveur que le corps est du JSON
            },
            // --- Le changement clé ici : envoyer les valeurs réelles des champs ---
            body: JSON.stringify({
                email: email, // Utilise la valeur dynamique du champ email
                motDePasse: motDePasse // Utilise la valeur dynamique du champ motDePasse
            })
            // --- Fin du changement clé ---
        })
            .then(response => {
                // Vérifie si la réponse n'est pas OK (par exemple, 4xx ou 5xx)
                if (!response.ok) {
                    // Si la réponse n'est pas OK, tente de lire le corps JSON
                    // ou lance une erreur avec le statut
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Erreur réseau');
                    }).catch(() => {
                        // Si le corps n'est pas un JSON valide, ou autre erreur
                        throw new Error(response.statusText || 'Erreur réseau');
                    });
                }
                return response.json(); // Traite la réponse JSON
            })
            .then(data => {
                if (data.success) {
                    // Connexion réussie - redirection
                    window.location.href = data.redirect;
                } else {
                    // Erreur de connexion - afficher le message
                    errorText.textContent = data.message;
                    errorMessage.classList.remove('d-none');

                    // Secouer le modal pour attirer l'attention
                    const modal = document.querySelector('.modal-content');
                    modal.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        modal.style.animation = '';
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Erreur de connexion:', error);
                errorText.textContent = error.message || 'Une erreur est survenue. Veuillez réessayer.'; // Affiche le message de l'erreur
                errorMessage.classList.remove('d-none');
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });
});