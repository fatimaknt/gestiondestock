<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends BaseController
{
    use AuthorizesRequests;
    public function __construct()
    {
        // Vérification des permissions dans chaque méthode
    }

        public function index(Request $request)
    {
        $this->checkPermission();

        $query = User::with(['shop', 'roles']);

        // Si l'utilisateur n'est pas admin, filtrer par boutique
        if (!Auth::user()->hasRole('admin')) {
            $query->where('shop_id', Auth::user()->shop_id);
        }

        // Recherche par nom, email ou username
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filtrage par rôle
        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('roles', function($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $status = $request->status === 'active';
            $query->where('is_active', $status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::where('guard_name', 'web')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    private function checkPermission()
    {
        $user = Auth::user();
        if (!$user || !$user->roles || !$user->roles->first() || !in_array($user->roles->first()->name, ['admin', 'vendeur'])) {
            abort(403, 'Accès non autorisé');
        }
    }

    public function create()
    {
        $this->checkPermission();

        // Seuls les admins peuvent créer des utilisateurs
        $currentUser = Auth::user();
        if (!$currentUser->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        // Admin peut créer tous les rôles sauf admin
        $roles = Role::where('guard_name', 'web')->where('name', '!=', 'admin')->get();
        $shops = Shop::all();

        return view('admin.users.create', compact('roles', 'shops'));
    }

    public function store(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Vérification de sécurité des rôles
        $currentUser = Auth::user();
        $requestedRole = $request->role;

        // Empêcher la création d'admins
        if ($requestedRole === 'admin') {
            return back()->with('error', 'La création de comptes administrateur n\'est pas autorisée');
        }

        // Seuls les admins peuvent créer des utilisateurs
        if (!$currentUser->hasRole('admin')) {
            return back()->with('error', 'Seuls les administrateurs peuvent créer des comptes');
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'shop_id' => null, // L'utilisateur créera sa propre boutique
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            $user->assignRole($request->role);

            // Envoyer un email avec les identifiants
            try {
                Mail::raw("Bonjour {$request->name},\n\nVotre compte a été créé avec succès.\n\nIdentifiants de connexion :\nEmail: {$request->email}\nMot de passe: {$request->password}\n\nVous pouvez maintenant vous connecter à l'application.\n\nCordialement,\nL'équipe de gestion", function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Vos identifiants de connexion - Gestion de Stock');
                });
            } catch (\Exception $e) {
                // Si l'email échoue, on continue quand même
                \Log::error('Erreur envoi email: ' . $e->getMessage());
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur créé avec succès. Email envoyé avec les identifiants.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $this->checkPermission();

        $user->load(['shop', 'roles', 'permissions']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->checkPermission();

        // Filtrer les rôles selon l'utilisateur connecté
        $currentUser = Auth::user();
        if ($currentUser->hasRole('admin')) {
            $roles = Role::where('guard_name', 'web')->get(); // Admin peut voir tous les rôles
        } else {
            $roles = Role::where('guard_name', 'web')->where('name', 'vendeur')->get(); // Vendeur ne peut voir que vendeur
        }

        $shops = Shop::all();

        return view('admin.users.edit', compact('user', 'roles', 'shops'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkPermission();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'shop_id' => 'required|exists:shops,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Vérification de sécurité des rôles
        $currentUser = Auth::user();
        $requestedRole = $request->role;

        // Seuls les admins peuvent promouvoir en admin
        if ($requestedRole === 'admin' && !$currentUser->hasRole('admin')) {
            return back()->with('error', 'Seuls les administrateurs peuvent promouvoir en administrateur');
        }

        // Un vendeur ne peut promouvoir qu'en vendeur
        if ($currentUser->hasRole('vendeur') && $requestedRole !== 'vendeur') {
            return back()->with('error', 'Vous ne pouvez promouvoir qu\'en vendeur');
        }

        try {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'shop_id' => $request->shop_id,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->syncRoles([$request->role]);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Utilisateur modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('update', $user);

        try {
            $user->update(['is_active' => !$user->is_active]);
            $status = $user->is_active ? 'activé' : 'désactivé';

            return back()->with('success', "Utilisateur {$status} avec succès");
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }
}
