<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Location;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index($id)
    {
        $org = Org::findOrFail($id);

        $activeLocations = Location::where('org_id', $org->id)
            ->where('active', 1)
            ->orderBy('order_by')
            ->get();

        return view('orgs.notifications.index', [
            'org' => $org,
            'activeLocations' => $activeLocations,
            'active' => 'notifications',
            'title' => 'Notificaciones',
        ]);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'send_methods' => 'nullable|array',
            'send_methods.*' => 'in:app,email,sms',
            'target_type' => 'required|in:sector,person',
            'sectors' => 'nullable|array',
            'sectors.*' => 'exists:locations,id',
        ]);

        $org = Org::findOrFail($id);

        $notification = new Notification();
        $notification->title = $validated['title'];
        $notification->message = $validated['message'];
        $notification->org_id = $org->id;
        $notification->created_by = Auth::id();
        $notification->status = 'pending'; 
        $notification->target_type = $validated['target_type'];
        $notification->recipient_id = null;
        $notification->recipient_name = null;
        $notification->send_app = in_array('app', $validated['send_methods']);
        $notification->send_email = in_array('email', $validated['send_methods']);
        $notification->send_sms = in_array('sms', $validated['send_methods']);
        $notification->app_status = 'pending';
        $notification->email_status = 'pending';
        $notification->sms_status = 'pending';
        $notification->app_sent_at = null;
        $notification->email_sent_at = null;
        $notification->sms_sent_at = null;
        $notification->app_read_at = null;
        $notification->app_error = null;
        $notification->email_error = null;
        $notification->sms_error = null;
        $notification->scheduled_at = now();

        $notification->save();

        if ($validated['target_type'] == 'sector' && isset($validated['sectors'])) {
            foreach ($validated['sectors'] as $sectorId) {
            }
        }

        return redirect()->route('orgs.notifications.index', ['id' => $org->id])
            ->with('success', 'Notificación enviada con éxito');
    }
}
