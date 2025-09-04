@extends('layouts.app-with-nav')

@section('title', 'Modifier le Profil de la Boutique')

@section('content')
    <div class="container-fluid pt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pencil me-2"></i>Modifier le Profil de la Boutique
                        </h5>
                        <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Messages Flash -->
                        @include('components.flash-messages')

                        <form action="{{ route('shops.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Informations Principales -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-info-circle me-2"></i>Informations Principales
                                    </h6>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom de la Boutique *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $shop->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description', $shop->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Logo</label>
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                            id="logo" name="logo" accept="image/*">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if ($shop->logo)
                                            <div class="mt-2">
                                                <small class="text-muted">Logo actuel :</small>
                                                <img src="{{ Storage::url($shop->logo) }}" alt="Logo actuel"
                                                    class="img-thumbnail ms-2" style="max-width: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Informations de Contact -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-envelope me-2"></i>Informations de Contact
                                    </h6>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $shop->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Téléphone *</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $shop->phone) }}"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="website" class="form-label">Site Web</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website" value="{{ old('website', $shop->website) }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-geo-alt me-2"></i>Adresse
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Adresse *</label>
                                                <input type="text"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    id="address" name="address"
                                                    value="{{ old('address', $shop->address) }}" required>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="city" class="form-label">Ville *</label>
                                                <input type="text"
                                                    class="form-control @error('city') is-invalid @enderror"
                                                    id="city" name="city"
                                                    value="{{ old('city', $shop->city ?? '') }}" required>
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="postal_code" class="form-label">Code Postal *</label>
                                                <input type="text"
                                                    class="form-control @error('postal_code') is-invalid @enderror"
                                                    id="postal_code" name="postal_code"
                                                    value="{{ old('postal_code', $shop->postal_code ?? '') }}" required>
                                                @error('postal_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Pays *</label>
                                                <input type="text"
                                                    class="form-control @error('country') is-invalid @enderror"
                                                    id="country" name="country"
                                                    value="{{ old('country', $shop->country ?? '') }}" required>
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personnalisation -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-palette me-2"></i>Personnalisation
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="primary_color" class="form-label">Couleur Principale</label>
                                                <input type="color"
                                                    class="form-control @error('primary_color') is-invalid @enderror"
                                                    id="primary_color" name="primary_color"
                                                    value="{{ old('primary_color', $shop->primary_color) }}">
                                                @error('primary_color')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="secondary_color" class="form-label">Couleur Secondaire</label>
                                                <input type="color"
                                                    class="form-control @error('secondary_color') is-invalid @enderror"
                                                    id="secondary_color" name="secondary_color"
                                                    value="{{ old('secondary_color', $shop->secondary_color) }}">
                                                @error('secondary_color')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="currency" class="form-label">Devise *</label>
                                                <select class="form-select @error('currency') is-invalid @enderror"
                                                    id="currency" name="currency" required>
                                                    <option value="CFA"
                                                        {{ old('currency', $shop->currency ?? 'CFA') == 'CFA' ? 'selected' : '' }}>
                                                        CFA</option>
                                                    <option value="EUR"
                                                        {{ old('currency', $shop->currency ?? 'CFA') == 'EUR' ? 'selected' : '' }}>
                                                        EUR</option>
                                                    <option value="USD"
                                                        {{ old('currency', $shop->currency ?? 'CFA') == 'USD' ? 'selected' : '' }}>
                                                        USD</option>
                                                    <option value="XOF"
                                                        {{ old('currency', $shop->currency ?? 'CFA') == 'XOF' ? 'selected' : '' }}>
                                                        XOF</option>
                                                </select>
                                                @error('currency')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personnalisation -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-palette me-2"></i>Personnalisation
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="primary_color" class="form-label">Couleur Principale</label>
                                                <input type="color"
                                                    class="form-control form-control-color @error('primary_color') is-invalid @enderror"
                                                    id="primary_color" name="primary_color"
                                                    value="{{ old('primary_color', $shop->primary_color ?? '#007bff') }}">
                                                @error('primary_color')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="secondary_color" class="form-label">Couleur Secondaire</label>
                                                <input type="color"
                                                    class="form-control form-control-color @error('secondary_color') is-invalid @enderror"
                                                    id="secondary_color" name="secondary_color"
                                                    value="{{ old('secondary_color', $shop->secondary_color ?? '#6c757d') }}">
                                                @error('secondary_color')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
