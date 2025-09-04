@extends('layouts.app-with-nav')

@section('title', 'Nouveau Client')

@section('content')
    <div class="container-fluid">
        <!-- Messages Flash -->
        @include('components.flash-messages')

        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user-plus me-2"></i>Nouveau Client
                </h1>
                <p class="text-muted">Ajoutez un nouveau client à votre base de données</p>
            </div>
            <div>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la Liste
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Formulaire Principal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informations du Client</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('clients.store') }}">
                            @csrf

                            <div class="row">
                                <!-- Informations de Base -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom Complet *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Optionnel mais recommandé pour les
                                            communications</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Genre</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
                                            <option value="">Sélectionner...</option>
                                            <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Masculin
                                            </option>
                                            <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Féminin
                                            </option>
                                            <option value="Autre" {{ old('gender') === 'Autre' ? 'selected' : '' }}>Autre
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Date de Naissance</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                            max="{{ date('Y-m-d') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Date maximale : aujourd'hui</small>
                                    </div>
                                </div>

                                <!-- Adresse -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Adresse</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="city" class="form-label">Ville</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city') }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label">Code Postal</label>
                                        <input type="text"
                                            class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                                            name="postal_code" value="{{ old('postal_code') }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label">Pays</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                                            id="country" name="country" value="{{ old('country', 'Sénégal') }}">
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                    placeholder="Informations supplémentaires, préférences, allergies...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Enregistrer le Client
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec Conseils -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-lightbulb me-2"></i>Conseils
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-primary">📝 Informations Essentielles</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• Le nom est obligatoire</li>
                                <li>• Email et téléphone facilitent le contact</li>
                                <li>• L'adresse aide à la livraison</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-success">🎯 Bonnes Pratiques</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• Vérifiez l'orthographe du nom</li>
                                <li>• Utilisez un format de téléphone standard</li>
                                <li>• Ajoutez des notes pour les préférences</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-warning">⚠️ Important</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• L'email doit être unique</li>
                                <li>• La date de naissance ne peut pas être future</li>
                                <li>• Le client sera automatiquement actif</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Statistiques Rapides -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="h4 text-primary mb-2" id="total-clients">-</div>
                            <p class="text-muted mb-0">Clients au total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mise à jour des statistiques en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            // Ici vous pourriez ajouter une requête AJAX pour récupérer les stats
            // Pour l'instant, on affiche juste un placeholder
            document.getElementById('total-clients').textContent = 'Chargement...';
        });
    </script>

    <style>
        .form-label {
            font-weight: 600;
            color: #5a5c69;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-header h6 {
            margin: 0;
            color: white !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
@endsection
