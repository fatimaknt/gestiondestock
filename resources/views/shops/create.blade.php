@extends('layouts.app-with-nav')

@section('title', 'Nouvelle Boutique')

@section('content')
    <div class="container-fluid pt-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('shops.index') }}" class="text-decoration-none">
                                        <i class="bi bi-shop me-1"></i>Mes Boutiques
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Nouvelle Boutique</li>
                            </ol>
                        </nav>
                        <h1 class="h2 fw-bold text-dark mb-1 mt-2">Nouvelle Boutique</h1>
                        <p class="text-muted mb-0">Créez une nouvelle boutique pour votre entreprise</p>
                    </div>
                    <div>
                        <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de création -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="bi bi-plus-circle me-2 text-primary"></i>
                            Informations de la Boutique
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('shops.store') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="row g-4">
                                <!-- Informations de base -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-shop me-2 text-primary"></i>Nom de la Boutique *
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Nom de votre boutique" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="bi bi-envelope me-2 text-primary"></i>Email *
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="email@boutique.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="bi bi-text-paragraph me-2 text-primary"></i>Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" placeholder="Description de votre boutique">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Adresse -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold">
                                        <i class="bi bi-geo-alt me-2 text-primary"></i>Adresse *
                                    </label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address') }}"
                                        placeholder="Adresse complète de la boutique" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ville et Code Postal -->
                                <div class="col-md-6">
                                    <label for="city" class="form-label fw-semibold">
                                        <i class="bi bi-building me-2 text-primary"></i>Ville *
                                    </label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}" placeholder="Ville"
                                        required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="postal_code" class="form-label fw-semibold">
                                        <i class="bi bi-mailbox me-2 text-primary"></i>Code Postal *
                                    </label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                        id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                        placeholder="Code postal" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Téléphone et Pays -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="bi bi-telephone me-2 text-primary"></i>Téléphone *
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}"
                                        placeholder="Téléphone de la boutique" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="country" class="form-label fw-semibold">
                                        <i class="bi bi-globe me-2 text-primary"></i>Pays *
                                    </label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country', 'Sénégal') }}"
                                        placeholder="Pays" required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Couleurs -->
                                <div class="col-md-6">
                                    <label for="primary_color" class="form-label fw-semibold">
                                        <i class="bi bi-palette me-2 text-primary"></i>Couleur Principale *
                                    </label>
                                    <input type="color"
                                        class="form-control form-control-color @error('primary_color') is-invalid @enderror"
                                        id="primary_color" name="primary_color"
                                        value="{{ old('primary_color', '#007bff') }}" required>
                                    @error('primary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="secondary_color" class="form-label fw-semibold">
                                        <i class="bi bi-palette me-2 text-primary"></i>Couleur Secondaire *
                                    </label>
                                    <input type="color"
                                        class="form-control form-control-color @error('secondary_color') is-invalid @enderror"
                                        id="secondary_color" name="secondary_color"
                                        value="{{ old('secondary_color', '#6c757d') }}" required>
                                    @error('secondary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Créer la Boutique
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
