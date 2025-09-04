<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::where('shop_id', Auth::user()->shop_id);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('type')) {
            switch ($request->type) {
                case 'vip':
                    $query->where('total_purchases', '>', 100000);
                    break;
                case 'regular':
                    $query->where('total_purchases', '>', 50000)->where('total_purchases', '<=', 100000);
                    break;
                case 'new':
                    $query->where('total_purchases', '<=', 50000);
                    break;
            }
        }

        $clients = $query->orderBy('total_purchases', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        // Statistiques
        $stats = [
            'total' => Client::where('shop_id', Auth::user()->shop_id)->count(),
            'active' => Client::where('shop_id', Auth::user()->shop_id)->where('is_active', true)->count(),
            'vip' => Client::where('shop_id', Auth::user()->shop_id)->where('total_purchases', '>', 100000)->count(),
            'total_revenue' => Client::where('shop_id', Auth::user()->shop_id)->sum('total_purchases')
        ];

        return view('clients.index', compact('clients', 'stats'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:M,F,Autre',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            Client::create([
                'shop_id' => Auth::user()->shop_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Sénégal',
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'notes' => $request->notes,
                'is_active' => true
            ]);

            return redirect()->route('clients.index')
                ->with('success', 'Client ajouté avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout du client: ' . $e->getMessage());
        }
    }

    public function show(Client $client)
    {
        // Vérifier que le client appartient à la boutique de l'utilisateur
        if ($client->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }

        $client->load(['sales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        // Vérifier que le client appartient à la boutique de l'utilisateur
        if ($client->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // Vérifier que le client appartient à la boutique de l'utilisateur
        if ($client->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:M,F,Autre',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        try {
            $client->update($request->all());

            return redirect()->route('clients.show', $client)
                ->with('success', 'Client modifié avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    public function destroy(Client $client)
    {
        // Vérifier que le client appartient à la boutique de l'utilisateur
        if ($client->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }

        try {
            $client->delete();

            return redirect()->route('clients.index')
                ->with('success', 'Client supprimé avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Client $client)
    {
        // Vérifier que le client appartient à la boutique de l'utilisateur
        if ($client->shop_id !== Auth::user()->shop_id) {
            abort(403);
        }

        try {
            $client->update(['is_active' => !$client->is_active]);

            $status = $client->is_active ? 'activé' : 'désactivé';
            return back()->with('success', "Client {$status} avec succès");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }
}
