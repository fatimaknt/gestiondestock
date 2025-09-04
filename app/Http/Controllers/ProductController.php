<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])
            ->where('shop_id', Auth::user()->shop_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expiry_date' => 'nullable|date|after:today',
            'unit' => 'required|string|max:50',
        ]);

        $data = $request->all();
        $data['shop_id'] = Auth::user()->shop_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès');
    }

    public function show(Product $product)
    {
        // Vérifier que l'utilisateur peut voir ce produit (même boutique)
        if ($product->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé à ce produit.');
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Vérifier que l'utilisateur peut modifier ce produit (même boutique)
        if ($product->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé à ce produit.');
        }

        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        // Vérifier que l'utilisateur peut modifier ce produit (même boutique)
        if ($product->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé à ce produit.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expiry_date' => 'nullable|date|after:today',
            'unit' => 'required|string|max:50',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès');
    }

    public function destroy(Product $product)
    {
        // Vérifier que l'utilisateur peut supprimer ce produit (même boutique)
        if ($product->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Accès non autorisé à ce produit.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = Product::with(['category', 'supplier'])
            ->where('shop_id', Auth::user()->shop_id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->get();

        return response()->json($products);
    }
}
