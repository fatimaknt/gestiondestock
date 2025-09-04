@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Informations Principales -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>Profil de {{ $user->name }}
                        </h5>
                        <div class="btn-group">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i>Modifier
                            </a>
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}"
                                        title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                        <i class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }} me-1"></i>
                                        {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Messages Flash -->
                        @include('components.flash-messages')

                        <div class="row">
                            <!-- Informations Personnelles -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person me-2"></i>Informations Personnelles
                                </h6>

                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Nom :</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email :</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Téléphone :</td>
                                        <td>{{ $user->phone ?? 'Non renseigné' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Adresse :</td>
                                        <td>{{ $user->address ?? 'Non renseignée' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Informations Système -->
                            <div class="col-md-6">
                                <h6 class="text-info mb-3">
                                    <i class="bi bi-gear me-2"></i>Informations Système
                                </h6>

                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Rôle :</td>
                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Boutique :</td>
                                        <td>
                                            <span class="text-primary">{{ $user->shop->name ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Statut :</td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Membre depuis :</td>
                                        <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Informations de Connexion -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-success mb-3">
                                    <i class="bi bi-clock-history me-2"></i>Activité de Connexion
                                </h6>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="bi bi-calendar-check fa-2x text-success mb-2"></i>
                                            <h6>Dernière Connexion</h6>
                                            <p class="mb-0">
                                                @if ($user->last_login_at)
                                                    {{ $user->last_login_at->format('d/m/Y') }}<br>
                                                    <small
                                                        class="text-muted">{{ $user->last_login_at->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">Jamais connecté</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="bi bi-pencil-square fa-2x text-warning mb-2"></i>
                                            <h6>Dernière Modification</h6>
                                            <p class="mb-0">
                                                {{ $user->updated_at->format('d/m/Y') }}<br>
                                                <small class="text-muted">{{ $user->updated_at->format('H:i') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="bi bi-shield-check fa-2x text-info mb-2"></i>
                                            <h6>Permissions</h6>
                                            <p class="mb-0">
                                                @foreach ($user->permissions as $permission)
                                                    <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                                                @endforeach
                                                @if ($user->permissions->count() == 0)
                                                    <span class="text-muted">Aucune permission spécifique</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques et Actions -->
            <div class="col-lg-4">
                <!-- Actions Rapides -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier le Profil
                            </a>
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }} w-100">
                                        <i class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }} me-2"></i>
                                        {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-trash me-2"></i>Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations de Sécurité -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Sécurité
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Mot de passe :</strong>
                            <span class="badge bg-secondary">Haché avec bcrypt</span>
                        </div>
                        <div class="mb-3">
                            <strong>Authentification :</strong>
                            <span class="badge bg-success">Laravel Sanctum</span>
                        </div>
                        <div class="mb-3">
                            <strong>Protection CSRF :</strong>
                            <span class="badge bg-success">Activée</span>
                        </div>
                        <div>
                            <strong>Validation :</strong>
                            <span class="badge bg-success">Stricte</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton Retour -->
        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Retour à la Liste
                </a>
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

        .text-success {
            color: #198754 !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .table-borderless td {
            padding: 0.5rem 0;
            border: none;
        }

        .fa-2x {
            font-size: 2em;
        }
    </style>
@endsection
