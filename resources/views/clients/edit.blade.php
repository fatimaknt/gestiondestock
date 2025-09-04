@extends('layouts.app-with-nav')

@section('title', 'Modifier le Client - ' . $client->name)

@section('content')
    <div class="container-fluid">
        <!-- Messages Flash -->
        @include('components.flash-messages')

        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-edit me-2"></i>Modifier {{ $client->name }}
                </h1>
                <p class="text-muted">Modifiez les informations du client</p>
            </div>
            <div>
                <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Retour aux Détails
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-list me-1"></i>Liste des Clients
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Formulaire Principal -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-edit me-2"></i>Modifier les Informations
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('clients.update', $client) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Informations de Base -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom Complet *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $client->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $client->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Optionnel mais recommandé pour les
                                            communications</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Genre</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
                                            <option value="">Sélectionner...</option>
                                            <option value="M"
                                                {{ old('gender', $client->gender) === 'M' ? 'selected' : '' }}>Masculin
                                            </option>
                                            <option value="F"
                                                {{ old('gender', $client->gender) === 'F' ? 'selected' : '' }}>Féminin
                                            </option>
                                            <option value="Autre"
                                                {{ old('gender', $client->gender) === 'Autre' ? 'selected' : '' }}>Autre
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Date de Naissance</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date" name="birth_date"
                                            value="{{ old('birth_date', $client->birth_date ? $client->birth_date->format('Y-m-d') : '') }}"
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
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $client->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="city" class="form-label">Ville</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $client->city) }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label">Code Postal</label>
                                        <input type="text"
                                            class="form-control @error('postal_code') is-invalid @enderror" id="postal_code"
                                            name="postal_code" value="{{ old('postal_code', $client->postal_code) }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label">Pays</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                                            id="country" name="country"
                                            value="{{ old('country', $client->country) }}">
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
                                    placeholder="Informations supplémentaires, préférences, allergies...">{{ old('notes', $client->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Mettre à Jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informations Actuelles -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-info-circle me-2"></i>Informations Actuelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-primary">📊 Statistiques</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• Chiffre d'affaires : {{ $client->total_purchases_formatted }}</li>
                                <li>• Nombre d'achats : {{ $client->sales_count ?? 0 }}</li>
                                <li>• Client depuis : {{ $client->created_at->format('d/m/Y') }}</li>
                                <li>• Dernière modification : {{ $client->updated_at->format('d/m/Y') }}</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-success">🎯 Statut</h6>
                            <span class="badge bg-{{ $client->status_badge_class }} fs-6">
                                {{ $client->status_label }}
                            </span>
                        </div>

                        @if ($client->last_purchase_date)
                            <div class="mb-3">
                                <h6 class="text-warning">🛒 Dernier Achat</h6>
                                <p class="mb-0">{{ $client->last_purchase_date->format('d/m/Y') }}</p>
                                <small class="text-muted">{{ $client->last_purchase_date->diffForHumans() }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Conseils de Modification -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-lightbulb me-2"></i>Conseils
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-primary">✏️ Modification</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• Modifiez uniquement les champs nécessaires</li>
                                <li>• Vérifiez l'orthographe des informations</li>
                                <li>• Les notes aident au suivi client</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-success">✅ Validation</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>• L'email doit rester unique</li>
                                <li>• La date de naissance ne peut pas être future</li>
                                <li>• Le nom est obligatoire</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-info">💡 Astuce</h6>
                            <p class="small text-muted mb-0">
                                Utilisez les notes pour enregistrer les préférences, allergies ou informations importantes
                                du client.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-bolt me-2"></i>Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-info">
                                <i class="fas fa-eye me-1"></i>Voir les Détails
                            </a>
                            <a href="{{ route('cashier.index') }}?client={{ $client->id }}" class="btn btn-primary">
                                <i class="fas fa-cash-register me-1"></i>Nouvelle Vente
                            </a>
                            <form method="POST" action="{{ route('clients.toggle-status', $client) }}" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $client->is_active ? 'danger' : 'success' }}">
                                    <i class="fas fa-{{ $client->is_active ? 'times' : 'check' }} me-1"></i>
                                    {{ $client->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        .badge {
            font-size: 0.875rem;
        }
    </style>
@endsection
