@extends('layouts.app')

@section('title', 'Notifications - Gestion de Stock')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-bell text-info me-2"></i>Notifications
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour au Dashboard
            </a>
        </div>

        <!-- Statistiques des notifications -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Notifications</h6>
                                <h3 class="mb-0">{{ $notifications->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-bell" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Non Lues</h6>
                                <h3 class="mb-0">{{ $notifications->where('read_at', null)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-bell-fill" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Lues</h6>
                                <h3 class="mb-0">{{ $notifications->where('read_at', '!=', null)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Aujourd'hui</h6>
                                <h3 class="mb-0">
                                    {{ $notifications->where('created_at', '>=', now()->startOfDay())->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions en masse -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success" onclick="markAllAsRead()">
                        <i class="bi bi-check-all me-2"></i>Marquer tout comme lu
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteAllRead()">
                        <i class="bi bi-trash me-2"></i>Supprimer les lues
                    </button>
                </div>
            </div>
        </div>

        <!-- Liste des notifications -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Liste des Notifications</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse ($notifications as $notification)
                        <div class="list-group-item border-0 py-3 {{ $notification->read_at ? 'bg-light' : 'bg-white' }}">
                            <div class="d-flex align-items-start">
                                <div
                                    class="bg-{{ $notification->read_at ? 'secondary' : 'info' }} bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                                    <i
                                        class="bi bi-bell{{ $notification->read_at ? '' : '-fill' }} text-{{ $notification->read_at ? 'secondary' : 'info' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1 fw-semibold {{ $notification->read_at ? 'text-muted' : '' }}">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </h6>
                                        <div class="btn-group btn-group-sm">
                                            @if (!$notification->read_at)
                                                <button class="btn btn-outline-success btn-sm"
                                                    onclick="markAsRead('{{ $notification->id }}')">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-danger btn-sm"
                                                onclick="deleteNotification('{{ $notification->id }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mb-2 {{ $notification->read_at ? 'text-muted' : '' }}">
                                        {{ $notification->data['message'] ?? 'Aucun message' }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                        @if (!$notification->read_at)
                                            <span class="badge bg-danger">Nouvelle</span>
                                        @else
                                            <span class="badge bg-success">Lue</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item border-0 py-5 text-center text-muted">
                            <div class="text-muted">
                                <i class="bi bi-bell-slash h1 text-muted mb-3"></i>
                                <h5>Aucune notification</h5>
                                <p class="mb-0">Vous n'avez pas encore reçu de notifications !</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if ($notifications->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>


@endsection
