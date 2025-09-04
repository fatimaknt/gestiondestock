<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        return view('purchases.index');
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('orders.index')
            ->with('success', 'Commande créée avec succès');
    }

    public function show($id)
    {
        return view('purchases.show', compact('id'));
    }

    public function receive($id)
    {
        return redirect()->route('orders.show', $id)
            ->with('success', 'Marchandises reçues avec succès');
    }
}
