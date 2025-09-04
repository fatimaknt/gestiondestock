<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('shop_id', Auth::user()->shop_id)
            ->orderBy('name')
            ->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['shop_id'] = Auth::user()->shop_id;
        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Fournisseur ajouté avec succès !');
    }

    public function show(Supplier $supplier)
    {
        if ($supplier->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé');
        }
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        if ($supplier->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé');
        }
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        if ($supplier->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Fournisseur mis à jour avec succès !');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier si le fournisseur a des produits
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Impossible de supprimer ce fournisseur car il a des produits associés.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Fournisseur supprimé avec succès !');
    }
}
