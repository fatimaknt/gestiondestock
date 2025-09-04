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
                                    <i class="bi bi-box-seam display-1 text-white"></i>
                                </div>
                                <h1 class="display-6 fw-bold mb-3">Gestion de Stock</h1>
                                <p class="lead mb-4 opacity-75">Solution professionnelle pour la gestion de votre inventaire
                                </p>
                                <div class="d-flex justify-content-center gap-3">
                                    <div class="text-center">
                                        <i class="bi bi-shield-check h4 mb-2"></i>
                                        <p class="small mb-0">Sécurisé</p>
                                    </div>
                                    <div class="text-center">
                                        <i class="bi bi-graph-up h4 mb-2"></i>
                                        <p class="small mb-0">Efficace</p>
                                    </div>
                                    <div class="text-center">
                                        <i class="bi bi-phone h4 mb-2"></i>
                                        <p class="small mb-0">Responsive</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne de droite - Formulaire -->
                        <div class="col-lg-6 bg-white p-5">
                            <div class="text-center mb-4">
                                <h2 class="h3 fw-bold text-dark mb-2">Connexion à votre compte</h2>
                                <p class="text-muted">Accédez à votre tableau de bord</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Erreur de connexion :</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.post') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="mb-4">
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

                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="bi bi-lock me-2 text-primary"></i>Mot de passe
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Votre mot de passe" required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label text-muted" for="remember">
                                            Se souvenir de moi
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}"
                                        class="text-decoration-none text-primary fw-semibold">
                                        Mot de passe oublié ?
                                    </a>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold py-3">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Se connecter
                                    </button>
                                </div>

                                <div class="text-center">
                                    <div class="text-center">
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-shield-lock me-2"></i>
                                            Inscription sécurisée
                                        </p>
                                        <p class="text-muted mb-2">
                                            Pour créer un compte, contactez l'administrateur :
                                        </p>
                                        <div class="bg-light p-3 rounded">
                                            <p class="mb-1">
                                                <i class="bi bi-envelope me-2"></i>
                                                <strong>Email :</strong> kanoutef163@gmail.com
                                            </p>
                                            <p class="mb-0">
                                                <i class="bi bi-telephone me-2"></i>
                                                <strong>Téléphone :</strong> +221 77 087 46 19
                                            </p>
                                        </div>
                                    </div>
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
                                        <i class="bi bi-github me-2"></i>
                                        GitHub
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-primary w-100 py-3 fw-semibold">
                                        <i class="bi bi-twitter me-2"></i>
                                        Twitter
                                    </button>
                                </div>
                            </div>

                            <!-- Informations de sécurité -->
                            <div class="mt-4 p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-shield-check me-2 text-success"></i>
                                    <span>Vos données sont protégées par un chiffrement SSL 256-bit</span>
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
    </script>
@endsection
