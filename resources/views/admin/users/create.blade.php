@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-plus me-2"></i>Créer un Nouvel Utilisateur
                        </h5>
                    </div>

                    <div class="card-body">
                        <!-- Messages Flash -->
                        @include('components.flash-messages')

                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf

                            <div class="row">
                                <!-- Informations Personnelles -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-person me-2"></i>Informations Personnelles
                                    </h6>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom Complet *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Nom d'utilisateur *</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username') }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                        <label for="address" class="form-label">Adresse</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Informations de Connexion -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-shield-lock me-2"></i>Informations de Connexion
                                    </h6>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de Passe *</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimum 8 caractères</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmer le Mot de Passe
                                            *</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Rôle *</label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role"
                                            name="role" required>
                                            <option value="">Sélectionner un rôle</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ old('role') == $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <strong>Note :</strong> L'utilisateur créera sa propre boutique après la
                                            connexion.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'Action -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Créer l'Utilisateur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .text-primary {
            color: #0d6efd !important;
        }
    </style>
@endsection
