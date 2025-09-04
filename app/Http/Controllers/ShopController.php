<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class ShopController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $shop = Auth::user()->shop;

        // Si l'utilisateur n'a pas de boutique, créer une boutique par défaut
        if (!$shop) {
            $shop = Shop::create([
                'name' => Auth::user()->name . ' - Boutique',
                'description' => 'Boutique par défaut',
                'address' => Auth::user()->address ?? 'Adresse non définie',
                'phone' => Auth::user()->phone ?? 'Téléphone non défini',
                'email' => Auth::user()->email,
                'user_id' => Auth::user()->id,
                'is_active' => true,
                'city' => 'Ville non définie',
                'postal_code' => '00000',
                'country' => 'Sénégal',
                'currency' => 'XOF',
                'timezone' => 'Africa/Dakar',
                'primary_color' => '#007bff',
                'secondary_color' => '#6c757d'
            ]);

            // Mettre à jour l'utilisateur avec le shop_id
            Auth::user()->update(['shop_id' => $shop->id]);
        }

        return view('shops.index', compact('shop'));
    }

    public function edit()
    {
        $shop = Auth::user()->shop;

        // Si l'utilisateur n'a pas de boutique, créer une boutique par défaut
        if (!$shop) {
            $shop = Shop::create([
                'name' => Auth::user()->name . ' - Boutique',
                'description' => 'Boutique par défaut',
                'address' => Auth::user()->address ?? 'Adresse non définie',
                'phone' => Auth::user()->phone ?? 'Téléphone non défini',
                'email' => Auth::user()->email,
                'user_id' => Auth::user()->id,
                'is_active' => true,
                'city' => 'Ville non définie',
                'postal_code' => '00000',
                'country' => 'Sénégal',
                'currency' => 'XOF',
                'timezone' => 'Africa/Dakar',
                'primary_color' => '#007bff',
                'secondary_color' => '#6c757d'
            ]);

            // Mettre à jour l'utilisateur avec le shop_id
            Auth::user()->update(['shop_id' => $shop->id]);
        }

        return view('shops.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = Auth::user()->shop;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'currency' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
        ]);

        try {
            $data = $request->except(['logo']);

            // Gestion du logo
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo s'il existe
                if ($shop->logo && Storage::disk('public')->exists($shop->logo)) {
                    Storage::disk('public')->delete($shop->logo);
                }

                // Stocker le nouveau logo
                $logoPath = $request->file('logo')->store('shops/logos', 'public');
                $data['logo'] = $logoPath;
            }

            $shop->update($data);

            return redirect()->route('shops.index')
                ->with('success', 'Informations de la boutique mises à jour avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function show()
    {
        $shop = Auth::user()->shop;
        return view('shops.show', compact('shop'));
    }
}
