@extends('layouts.app-with-nav')

@section('title', 'Profil de la Boutique')

@section('content')
    <div class="container-fluid pt-4">
        <div class="row">
            <!-- Informations de la Boutique -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shop me-2"></i>Profil de la Boutique
                        </h5>
                        <a href="{{ route('shops.edit') }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Messages Flash -->
                        @include('components.flash-messages')

                        <div class="row">
                            <!-- Logo et Informations Principales -->
                            <div class="col-md-4 text-center">
                                <div class="mb-4">
                                    @if ($shop->logo)
                                        <img src="{{ Storage::url($shop->logo) }}" alt="Logo {{ $shop->name }}"
                                            class="img-fluid rounded" style="max-width: 200px; max-height: 200px;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 200px; height: 200px;">
                                            <i class="bi bi-shop fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <h4 class="text-primary">{{ $shop->name }}</h4>
                                @if ($shop->description)
                                    <p class="text-muted">{{ $shop->description }}</p>
                                @endif
                            </div>

                            <!-- Détails de la Boutique -->
                            <div class="col-md-8">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Informations Générales
                                </h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold">Email :</td>
                                                <td>{{ $shop->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Téléphone :</td>
                                                <td>{{ $shop->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Site Web :</td>
                                                <td>
                                                    @if ($shop->website)
                                                        <a href="{{ $shop->website }}"
                                                            target="_blank">{{ $shop->website }}</a>
                                                    @else
                                                        <span class="text-muted">Non renseigné</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Devise :</td>
                                                <td><span class="badge bg-success">{{ $shop->currency ?? 'CFA' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold">Adresse :</td>
                                                <td>{{ $shop->address }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Ville :</td>
                                                <td>{{ $shop->city }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Code Postal :</td>
                                                <td>{{ $shop->postal_code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Pays :</td>
                                                <td>{{ $shop->country }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personnalisation -->
                        @if ($shop->primary_color || $shop->secondary_color)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-info mb-3">
                                        <i class="bi bi-palette me-2"></i>Personnalisation
                                    </h6>

                                    <div class="row">
                                        @if ($shop->primary_color)
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="color-preview mb-2"
                                                        style="width: 50px; height: 50px; background-color: {{ $shop->primary_color }}; border-radius: 50%; margin: 0 auto;">
                                                    </div>
                                                    <small class="text-muted">Couleur Principale</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($shop->secondary_color)
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="color-preview mb-2"
                                                        style="width: 50px; height: 50px; background-color: {{ $shop->secondary_color }}; border-radius: 50%; margin: 0 auto;">
                                                    </div>
                                                    <small class="text-muted">Couleur Secondaire</small>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="bg-light rounded p-3">
                                                    <i class="bi bi-clock fa-2x text-info mb-2"></i>
                                                    <h6>Fuseau Horaire</h6>
                                                    <small class="text-muted">{{ $shop->timezone ?? 'Non défini' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistiques et Actions -->
            <div class="col-lg-4">
                <!-- Statistiques Rapides -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>Statistiques Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-primary text-white rounded p-3">
                                    <i class="bi bi-box fa-2x mb-2"></i>
                                    <h4>{{ $shop->products()->count() ?? 0 }}</h4>
                                    <small>Produits</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-success text-white rounded p-3">
                                    <i class="bi bi-people fa-2x mb-2"></i>
                                    <h4>{{ $shop->users()->count() ?? 0 }}</h4>
                                    <small>Utilisateurs</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-info text-white rounded p-3">
                                    <i class="bi bi-cart fa-2x mb-2"></i>
                                    <h4>{{ $shop->sales()->count() ?? 0 }}</h4>
                                    <small>Ventes</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-warning text-white rounded p-3">
                                    <i class="bi bi-truck fa-2x mb-2"></i>
                                    <h4>{{ $shop->suppliers()->count() ?? 0 }}</h4>
                                    <small>Fournisseurs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('shops.edit') }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier le Profil
                            </a>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Ajouter un Produit
                            </a>
                            <a href="{{ route('clients.create') }}" class="btn btn-success">
                                <i class="bi bi-person-plus me-2"></i>Ajouter un Client
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-info">
                                <i class="bi bi-speedometer2 me-2"></i>Tableau de Bord
                            </a>
                        </div>
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

        .text-info {
            color: #0dcaf0 !important;
        }

        .color-preview {
            border: 2px solid #dee2e6;
        }

        .fa-2x {
            font-size: 2em;
        }

        .table-borderless td {
            padding: 0.5rem 0;
            border: none;
        }
    </style>
@endsection
