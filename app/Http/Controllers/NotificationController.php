<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShowNotification;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request, $patientId)
    {
        $notifications = Notification::where('patient_id', $patientId)->get();
        return ShowNotification::collection($notifications);
    }
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);

        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Уведомление помечено как прочитанное']);
    }
}
