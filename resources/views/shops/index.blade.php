@extends('layouts.app-with-nav')

@section('title', 'Mes Boutiques')

@section('content')
    <div class="container-fluid pt-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold text-dark mb-1">
                            <i class="bi bi-shop me-2 text-primary"></i>Mes Boutiques
                        </h1>
                        <p class="text-muted mb-0">Gérez toutes vos boutiques depuis un seul endroit</p>
                    </div>
                    <div>
                        <a href="{{ route('shops.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Nouvelle Boutique
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Flash -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Liste des Boutiques -->
        <div class="row">
            @foreach ($shops as $shop)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 {{ $shop->id == Auth::user()->shop_id ? 'border-primary' : '' }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-shop me-2"></i>{{ $shop->name }}
                                @if ($shop->id == Auth::user()->shop_id)
                                    <span class="badge bg-primary ms-2">Active</span>
                                @endif
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @if ($shop->id != Auth::user()->shop_id)
                                        <li><a class="dropdown-item" href="{{ route('shops.switch', $shop->id) }}">
                                                <i class="bi bi-arrow-right-circle me-2"></i>Activer
                                            </a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('shops.edit') }}">
                                            <i class="bi bi-pencil me-2"></i>Modifier
                                        </a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Logo -->
                            <div class="text-center mb-3">
                                @if ($shop->logo)
                                    <img src="{{ Storage::url($shop->logo) }}" alt="Logo {{ $shop->name }}"
                                        class="img-fluid rounded" style="max-width: 80px; max-height: 80px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 80px; height: 80px;">
                                        <i class="bi bi-shop fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Informations -->
                            <div class="text-center mb-3">
                                <h6 class="text-primary mb-1">{{ $shop->name }}</h6>
                                @if ($shop->description)
                                    <p class="text-muted small mb-2">{{ Str::limit($shop->description, 50) }}</p>
                                @endif
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $shop->city }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="text-center">
                                @if ($shop->id != Auth::user()->shop_id)
                                    <a href="{{ route('shops.switch', $shop->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-arrow-right-circle me-1"></i>Activer
                                    </a>
                                @else
                                    <span class="badge bg-success">Boutique Active</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($shops->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-shop fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucune boutique</h4>
                <p class="text-muted">Créez votre première boutique pour commencer</p>
                <a href="{{ route('shops.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer ma première boutique
                </a>
            </div>
        @endif
    </div>
@endsection
