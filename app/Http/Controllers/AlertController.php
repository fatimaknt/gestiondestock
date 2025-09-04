<?php

namespace App\Http\Controllers;

use App\Models\StockAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = StockAlert::where('shop_id', Auth::user()->shop_id)
            ->with(['product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('alerts.index', compact('alerts'));
    }

    public function stockAlerts()
    {
        $alerts = StockAlert::where('shop_id', Auth::user()->shop_id)
            ->with(['product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('alerts.stock', compact('alerts'));
    }

    public function markAsRead($id)
    {
        $alert = StockAlert::where('shop_id', Auth::user()->shop_id)
            ->findOrFail($id);

        $alert->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Alerte marquée comme lue'
        ]);
    }

    public function destroy($id)
    {
        $alert = StockAlert::where('shop_id', Auth::user()->shop_id)
            ->findOrFail($id);

        $alert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alerte supprimée avec succès'
        ]);
    }
}
