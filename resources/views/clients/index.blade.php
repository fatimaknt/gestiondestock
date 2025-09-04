@extends('layouts.app-with-nav')

@section('title', 'Gestion des Clients')

@section('content')
    <div class="container-fluid pt-4">
        <!-- Messages Flash -->
        @include('components.flash-messages')

        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-people me-2"></i>Gestion des Clients
                </h1>
                <p class="text-muted">Gérez votre base de clients et suivez leurs achats</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <a href="{{ route('clients.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Nouveau Client
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Clients</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Clients Actifs</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-person-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Clients VIP</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['vip'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-award fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Chiffre d'Affaires</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_revenue'], 0, ',', ' ') }} CFA</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtres et Recherche</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('clients.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Nom, email ou téléphone...">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tous</option>
                            <option value="vip" {{ request('type') === 'vip' ? 'selected' : '' }}>VIP</option>
                            <option value="regular" {{ request('type') === 'regular' ? 'selected' : '' }}>Réguliers
                            </option>
                            <option value="new" {{ request('type') === 'new' ? 'selected' : '' }}>Nouveaux</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des Clients -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des Clients</h6>
                <span class="badge bg-primary">{{ $clients->total() }} client(s)</span>
            </div>
            <div class="card-body">
                @if ($clients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Contact</th>
                                    <th>Localisation</th>
                                    <th>Statut</th>
                                    <th>Chiffre d'Affaires</th>
                                    <th>Dernier Achat</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div
                                                        class="avatar-title bg-{{ $client->gender === 'M' ? 'primary' : ($client->gender === 'F' ? 'danger' : 'secondary') }} rounded-circle">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $client->name }}</h6>
                                                    @if ($client->birth_date)
                                                        <small class="text-muted">{{ $client->age }} ans</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($client->email)
                                                <div><i class="bi bi-envelope me-1 text-muted"></i>{{ $client->email }}
                                                </div>
                                            @endif
                                            @if ($client->phone)
                                                <div><i class="bi bi-telephone me-1 text-muted"></i>{{ $client->phone }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($client->full_address)
                                                <small class="text-muted">{{ $client->full_address }}</small>
                                            @else
                                                <span class="text-muted">Non renseigné</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $client->status_badge_class }}">
                                                {{ $client->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $client->total_purchases_formatted }}</div>
                                            @if ($client->total_purchases > 0)
                                                <small class="text-muted">{{ $client->sales_count ?? 0 }} achat(s)</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($client->last_purchase_date)
                                                <div class="text-success">
                                                    {{ $client->last_purchase_date->format('d/m/Y') }}</div>
                                                <small
                                                    class="text-muted">{{ $client->last_purchase_date->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Aucun achat</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('clients.show', $client) }}"
                                                    class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                    <i class="bi bi-eye me-1"></i>Voir
                                                </a>
                                                <a href="{{ route('clients.edit', $client) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil me-1"></i>Modifier
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('clients.toggle-status', $client) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-{{ $client->is_active ? 'danger' : 'success' }}"
                                                        title="{{ $client->is_active ? 'Désactiver' : 'Activer' }}">
                                                        <i
                                                            class="bi bi-{{ $client->is_active ? 'x-circle' : 'check-circle' }} me-1"></i>
                                                        {{ $client->is_active ? 'Désactiver' : 'Activer' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $clients->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun client trouvé</h5>
                        <p class="text-muted">Commencez par ajouter votre premier client</p>
                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Ajouter un Client
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 16px;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fc;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
@endsection
