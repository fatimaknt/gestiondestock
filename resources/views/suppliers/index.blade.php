@extends('layouts.app-with-nav')

@section('title', 'Gestion des Fournisseurs')

@section('content')
    <div class="container-fluid pt-4">
        <!-- Messages Flash -->
        @include('components.flash-messages')

        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-truck text-primary me-2"></i>Gestion des Fournisseurs
            </h2>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nouveau Fournisseur
            </a>
        </div>

        <!-- Liste des fournisseurs -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if ($suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $supplier->name }}</h6>
                                                    @if ($supplier->notes)
                                                        <small
                                                            class="text-muted">{{ Str::limit($supplier->notes, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>
                                            <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                                {{ $supplier->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                                {{ $supplier->phone }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($supplier->address, 30) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('suppliers.show', $supplier) }}"
                                                    class="btn btn-outline-info btn-sm" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                                    class="btn btn-outline-warning btn-sm" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Supprimer">
                                                        <i class="bi bi-trash"></i>
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
                        {{ $suppliers->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-truck display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">Aucun fournisseur trouvé</h4>
                        <p class="text-muted">Commencez par ajouter votre premier fournisseur.</p>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Ajouter un Fournisseur
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
