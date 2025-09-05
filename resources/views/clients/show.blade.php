@extends('layouts.app-with-nav')

@section('title', 'Détails du Client - ' . $client->name)

@section('content')
    <div class="container-fluid">
        <!-- Messages Flash -->
        @include('components.flash-messages')

        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user me-2"></i>{{ $client->name }}
                </h1>
                <p class="text-muted">Détails complets du client</p>
            </div>
            <div>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la Liste
                </a>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
                <form method="POST" action="{{ route('clients.toggle-status', $client) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $client->is_active ? 'danger' : 'success' }}">
                        <i class="fas fa-{{ $client->is_active ? 'times' : 'check' }} me-1"></i>
                        {{ $client->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Informations Principales -->
            <div class="col-lg-8">
                <!-- Carte d'Identité -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-id-card me-2"></i>Carte d'Identité
                        </h6>
                        <span class="badge bg-{{ $client->status_badge_class }} fs-6">
                            {{ $client->status_label }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-lg me-3">
                                        <div
                                            class="avatar-title bg-{{ $client->gender === 'M' ? 'primary' : ($client->gender === 'F' ? 'danger' : 'secondary') }} rounded-circle">
                                            <i class="fas fa-user text-white fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">{{ $client->name }}</h4>
                                        <p class="text-muted mb-0">
                                            @if ($client->gender === 'M')
                                                <i class="fas fa-mars text-primary me-1"></i>Homme
                                            @elseif($client->gender === 'F')
                                                <i class="fas fa-venus text-danger me-1"></i>Femme
                                            @else
                                                <i class="fas fa-user text-secondary me-1"></i>Non spécifié
                                            @endif
                                            @if ($client->birth_date)
                                                • {{ $client->age }} ans
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-end">
                                    <div class="h5 text-primary mb-2">{{ $client->total_purchases_formatted }}</div>
                                    <p class="text-muted mb-0">Chiffre d'affaires total</p>
                                    @if ($client->total_purchases > 0)
                                        <small class="text-success">
                                            <i class="fas fa-chart-line me-1"></i>
                                            {{ $client->sales_count ?? 0 }} achat(s)
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de Contact -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-address-book me-2"></i>Informations de Contact
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($client->email)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-info me-3">
                                            <i class="fas fa-envelope text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Email</h6>
                                            <a href="mailto:{{ $client->email }}"
                                                class="text-info">{{ $client->email }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if ($client->phone)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-success me-3">
                                            <i class="fas fa-phone text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Téléphone</h6>
                                            <a href="tel:{{ $client->phone }}"
                                                class="text-success">{{ $client->phone }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                @if ($client->birth_date)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-warning me-3">
                                            <i class="fas fa-birthday-cake text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Date de Naissance</h6>
                                            <p class="mb-0">{{ $client->birth_date->format('d/m/Y') }}</p>
                                            <small class="text-muted">{{ $client->birth_date->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle bg-secondary me-3">
                                        <i class="fas fa-calendar-plus text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Client depuis</h6>
                                        <p class="mb-0">{{ $client->created_at->format('d/m/Y') }}</p>
                                        <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                @if ($client->full_address)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-success">
                                <i class="fas fa-map-marker-alt me-2"></i>Adresse
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="icon-circle bg-success me-3">
                                    <i class="fas fa-home text-white"></i>
                                </div>
                                <div>
                                    <p class="mb-1">{{ $client->address }}</p>
                                    <p class="mb-1">
                                        <strong>{{ $client->city }}</strong>
                                        @if ($client->postal_code)
                                            - {{ $client->postal_code }}
                                        @endif
                                    </p>
                                    <p class="mb-0 text-muted">{{ $client->country }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if ($client->notes)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-warning">
                                <i class="fas fa-sticky-note me-2"></i>Notes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="icon-circle bg-warning me-3">
                                    <i class="fas fa-comment text-white"></i>
                                </div>
                                <div>
                                    <p class="mb-0">{{ $client->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistiques du Client -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="h4 text-primary mb-1">{{ $client->sales_count ?? 0 }}</div>
                                <small class="text-muted">Achats</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 text-success mb-1">{{ $client->total_purchases > 0 ? 'Oui' : 'Non' }}</div>
                                <small class="text-muted">A déjà acheté</small>
                            </div>
                        </div>

                        @if ($client->total_purchases > 0)
                            <div class="border-top pt-3">
                                <div class="text-center mb-2">
                                    <div class="h5 text-warning mb-1">{{ $client->total_purchases_formatted }}</div>
                                    <small class="text-muted">Chiffre d'affaires</small>
                                </div>

                                @if ($client->total_purchases > 100000)
                                    <div class="alert alert-success text-center py-2">
                                        <i class="fas fa-crown me-1"></i>
                                        <strong>Client VIP</strong>
                                    </div>
                                @elseif($client->total_purchases > 50000)
                                    <div class="alert alert-info text-center py-2">
                                        <i class="fas fa-star me-1"></i>
                                        <strong>Client Fidèle</strong>
                                    </div>
                                @elseif($client->total_purchases > 10000)
                                    <div class="alert alert-warning text-center py-2">
                                        <i class="fas fa-heart me-1"></i>
                                        <strong>Client Régulier</strong>
                                    </div>
                                @else
                                    <div class="alert alert-secondary text-center py-2">
                                        <i class="fas fa-user me-1"></i>
                                        <strong>Nouveau Client</strong>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dernier Achat -->
                @if ($client->last_purchase_date)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-success">
                                <i class="fas fa-shopping-cart me-2"></i>Dernier Achat
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="h5 text-success mb-2">{{ $client->last_purchase_date->format('d/m/Y') }}</div>
                            <p class="text-muted mb-0">{{ $client->last_purchase_date->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Actions Rapides -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-bolt me-2"></i>Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('cashier.index') }}?client={{ $client->id }}" class="btn btn-primary">
                                <i class="fas fa-cash-register me-1"></i>Nouvelle Vente
                            </a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>Modifier
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
        .avatar-lg {
            width: 60px;
            height: 60px;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-header h6 {
            margin: 0;
            color: white !important;
        }

        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
    </style>
@endsection
