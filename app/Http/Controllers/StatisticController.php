<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function getGeneralStats(Request $request)
    {
        $completedAppointments = Appointment::query()
            ->where('status', 'Завершён');

        // Фильтр по месяцу, если надо
        if ($request->get('period') === 'month') {
            $completedAppointments->whereMonth('appointments.created_at', now()->month)
                ->whereYear('appointments.created_at', now()->year);
        }

        $totalCompleted = $completedAppointments->count();

        $totalRevenue = (clone $completedAppointments)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();

        $averagePrice = $totalCompleted > 0 ? $totalRevenue / $totalCompleted : 0;

        return response()->json([
            'total_completed_appointments' => $totalCompleted,
            'total_revenue' => round($totalRevenue, 2),
            'average_appointment_price' => round($averagePrice, 2),
            'total_patients' => $totalPatients,
            'total_doctors' => $totalDoctors,
        ]);
    }
}
