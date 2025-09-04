@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people me-2"></i>Gestion des Utilisateurs
                        </h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Nouvel Utilisateur
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Messages Flash -->
                        @include('components.flash-messages')

                        <!-- Filtres et Recherche -->
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Rechercher</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Nom, email...">
                            </div>
                            <div class="col-md-3">
                                <label for="role" class="form-label">Rôle</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Tous les rôles</option>
                                    @foreach ($roles ?? [] as $role)
                                        <option value="{{ $role->name }}"
                                            {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>Filtrer
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Tableau des Utilisateurs -->
                        @if ($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Boutique</th>
                                            <th>Statut</th>
                                            <th>Dernière Connexion</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                                <i class="bi bi-person"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $user->phone ?? 'Téléphone non renseigné' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-break">{{ $user->email }}</span>
                                                </td>
                                                <td>
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span class="text-primary">{{ $user->shop->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($user->last_login_at)
                                                        <div class="text-success">
                                                            {{ $user->last_login_at->format('d/m/Y') }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">Jamais connecté</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.users.show', $user) }}"
                                                            class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                            <i class="bi bi-eye me-1"></i>Voir
                                                        </a>
                                                        <a href="{{ route('admin.users.edit', $user) }}"
                                                            class="btn btn-sm btn-outline-warning" title="Modifier">
                                                            <i class="bi bi-pencil me-1"></i>Modifier
                                                        </a>
                                                        @if ($user->id !== auth()->id())
                                                            <form method="POST"
                                                                action="{{ route('admin.users.toggle-status', $user) }}"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}"
                                                                    title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                                                    <i
                                                                        class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }} me-1"></i>
                                                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                                                </button>
                                                            </form>
                                                            <form method="POST"
                                                                action="{{ route('admin.users.destroy', $user) }}"
                                                                class="d-inline"
                                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="Supprimer">
                                                                    <i class="bi bi-trash me-1"></i>Supprimer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-people fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                                <p class="text-muted">Commencez par ajouter votre premier utilisateur</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Ajouter un Utilisateur
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
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

        .text-break {
            word-break: break-word;
        }
    </style>
@endsection
