<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', 'App\Models\User')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Convertir les dates en objets Carbon après pagination
        $notifications->getCollection()->transform(function ($notification) {
            $notification->created_at = Carbon::parse($notification->created_at);
            $notification->updated_at = Carbon::parse($notification->updated_at);
            if ($notification->read_at) {
                $notification->read_at = Carbon::parse($notification->read_at);
            }
            return $notification;
        });

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', 'App\Models\User')
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification non trouvée'
            ], 404);
        }

        DB::table('notifications')
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue'
        ]);
    }

    public function destroy($id)
    {
        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', 'App\Models\User')
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Notification non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée avec succès'
        ]);
    }
}
