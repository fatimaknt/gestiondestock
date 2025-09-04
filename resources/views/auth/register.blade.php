@extends('layouts.app')

@section('content')
    <div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-0 shadow-lg rounded-4 overflow-hidden">
                        <!-- Colonne de gauche - Branding -->
                        <div
                            class="col-lg-6 bg-gradient-primary d-flex align-items-center justify-content-center p-5 text-white">
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="bi bi-person-plus display-1 text-white"></i>
                                </div>
                                <h1 class="display-6 fw-bold mb-3">Rejoignez-nous</h1>
                                <p class="lead mb-4 opacity-75">Créez votre compte et commencez à gérer votre stock
                                    professionnellement</p>

                                <!-- Avantages -->
                                <div class="row g-3 text-start">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-success me-3 h5"></i>
                                            <span>Gestion complète de l'inventaire</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-success me-3 h5"></i>
                                            <span>Rapports et analyses en temps réel</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-success me-3 h5"></i>
                                            <span>Interface intuitive et responsive</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-success me-3 h5"></i>
                                            <span>Sécurité et sauvegarde automatique</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne de droite - Formulaire -->
                        <div class="col-lg-6 bg-white p-5">
                            <div class="text-center mb-4">
                                <h2 class="h3 fw-bold text-dark mb-2">Créer votre compte</h2>
                                <p class="text-muted">Rejoignez des milliers d'entreprises qui nous font confiance</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Erreur d'inscription :</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register.post') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-person me-2 text-primary"></i>Nom complet
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person text-muted"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control border-start-0 @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}"
                                                placeholder="Votre nom complet" required>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="username" class="form-label fw-semibold">
                                            <i class="bi bi-at me-2 text-primary"></i>Nom d'utilisateur
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-at text-muted"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control border-start-0 @error('username') is-invalid @enderror"
                                                id="username" name="username" value="{{ old('username') }}"
                                                placeholder="nom_utilisateur" required>
                                        </div>
                                        @error('username')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="bi bi-envelope me-2 text-primary"></i>Adresse email
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email"
                                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="votre@email.com" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="shop_name" class="form-label fw-semibold">
                                        <i class="bi bi-shop me-2 text-primary"></i>Nom de votre boutique
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-shop text-muted"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control border-start-0 @error('shop_name') is-invalid @enderror"
                                            id="shop_name" name="shop_name" value="{{ old('shop_name') }}"
                                            placeholder="Ex: Boutique Aurya" required>
                                    </div>
                                    @error('shop_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="bi bi-lock me-2 text-primary"></i>Mot de passe
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock text-muted"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control border-start-0 @error('password') is-invalid @enderror"
                                                id="password" name="password" placeholder="8 caractères minimum"
                                                required>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            <i class="bi bi-lock-fill me-2 text-primary"></i>Confirmer
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-lock-fill text-muted"></i>
                                            </span>
                                            <input type="password" class="form-control border-start-0"
                                                id="password_confirmation" name="password_confirmation"
                                                placeholder="Répétez le mot de passe" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Indicateur de force du mot de passe -->
                                <div class="mb-3">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" id="password-strength" role="progressbar"
                                            style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="password-feedback">Force du mot de passe</small>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label text-muted" for="terms">
                                            J'accepte les <a href="#"
                                                class="text-decoration-none text-primary">Conditions d'utilisation</a>
                                            et la <a href="#" class="text-decoration-none text-primary">Politique de
                                                confidentialité</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold py-3">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Créer le compte
                                    </button>
                                </div>

                                <div class="text-center">
                                    <p class="text-muted mb-0">
                                        Déjà un compte ?
                                        <a href="{{ route('login') }}"
                                            class="text-decoration-none fw-semibold text-primary">
                                            Se connecter
                                        </a>
                                    </p>
                                </div>
                            </form>

                            <!-- Séparateur -->
                            <div class="text-center my-4">
                                <span class="bg-white px-3 text-muted">ou</span>
                                <hr class="position-absolute w-100" style="top: 50%; z-index: -1;">
                            </div>

                            <!-- Connexion sociale -->
                            <div class="row g-3">
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary w-100 py-3 fw-semibold">
                                        <i class="bi bi-google me-2"></i>
                                        Google
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-primary w-100 py-3 fw-semibold">
                                        <i class="bi bi-facebook me-2"></i>
                                        Facebook
                                    </button>
                                </div>
                            </div>

                            <!-- Informations de sécurité -->
                            <div class="mt-4 p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-shield-check me-2 text-success"></i>
                                    <span>Vos données sont protégées et ne seront jamais partagées</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .input-group-text {
            border-color: #e2e8f0;
        }

        .form-control {
            border-color: #e2e8f0;
        }

        .form-control:focus+.input-group-text {
            border-color: #667eea;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            transform: translateY(-1px);
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transform: translateY(-1px);
        }

        /* Animation d'entrée */
        .col-lg-6 {
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .col-lg-6:first-child {
                display: none;
            }

            .col-lg-10 {
                max-width: 500px;
            }
        }

        /* Styles pour l'indicateur de force du mot de passe */
        .progress {
            background-color: #f1f5f9;
        }

        .progress-bar {
            transition: all 0.3s ease;
        }
    </style>

    <script>
        // Validation Bootstrap
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Animation des boutons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'all 0.3s ease';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Indicateur de force du mot de passe
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength');
            const feedback = document.getElementById('password-feedback');

            let strength = 0;
            let color = '';
            let message = '';

            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/)) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;

            if (strength <= 25) {
                color = '#dc2626';
                message = 'Très faible';
            } else if (strength <= 50) {
                color = '#d97706';
                message = 'Faible';
            } else if (strength <= 75) {
                color = '#059669';
                message = 'Moyen';
            } else {
                color = '#059669';
                message = 'Fort';
            }

            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = color;
            feedback.textContent = message;
            feedback.style.color = color;
        });
    </script>
@endsection
