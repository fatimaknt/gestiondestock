<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'shop_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shop = Shop::create([
            'name' => $request->shop_name,
            'description' => 'Boutique créée par ' . $request->name,
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'vendeur',
            'shop_id' => $shop->id,
            'is_active' => true,
        ]);

        $user->assignRole('vendeur');

        // Connecter l'utilisateur automatiquement
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Compte créé avec succès ! Bienvenue dans votre tableau de bord.');
    }

    public function login(Request $request)
    {
        \Log::info('=== LOGIN ATTEMPT STARTED ===');
        \Log::info('Email: ' . $request->email);
        \Log::info('Password length: ' . strlen($request->password));

        try {
            // Vérification basique
            if (empty($request->email) || empty($request->password)) {
                \Log::info('Empty email or password');
                return redirect()->back()
                    ->withErrors(['email' => 'Email et mot de passe requis.'])
                    ->withInput();
            }

            // Test de connexion à la base de données
            try {
                $userCount = User::count();
                \Log::info('Users in database: ' . $userCount);
            } catch (\Exception $e) {
                \Log::error('Database connection error: ' . $e->getMessage());
                return redirect()->back()
                    ->withErrors(['email' => 'Erreur de connexion à la base de données.'])
                    ->withInput();
            }

            // Recherche de l'utilisateur
            $user = User::where('email', $request->email)->first();
            \Log::info('User found: ' . ($user ? 'YES' : 'NO'));

            if (!$user) {
                \Log::info('User not found: ' . $request->email);
                return redirect()->back()
                    ->withErrors(['email' => 'Utilisateur non trouvé.'])
                    ->withInput();
            }

            // Vérification du mot de passe
            if (!Hash::check($request->password, $user->password)) {
                \Log::info('Password incorrect for: ' . $request->email);
                return redirect()->back()
                    ->withErrors(['email' => 'Mot de passe incorrect.'])
                    ->withInput();
            }

            // Connexion manuelle
            Auth::login($user);
            \Log::info('User logged in successfully: ' . $user->email);

            return redirect()->route('dashboard')
                ->with('success', 'Connexion réussie !');

        } catch (\Exception $e) {
            \Log::error('=== LOGIN ERROR ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withErrors(['email' => 'Erreur: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load('shop', 'roles', 'permissions')
        ]);
    }
}
