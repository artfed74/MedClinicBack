<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function doctorsStats(Request $request)
    {
        $period = $request->query('period', 'all');

        $query = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(
                'appointments.doctor_id',
                DB::raw('COUNT(appointments.id) as total_appointments'),
                DB::raw('SUM(services.price) as total_revenue')
            )
            ->where('appointments.status', 'Завершён')
            ->groupBy('appointments.doctor_id');

        if ($period === 'month') {
            $query->whereMonth('appointments.appointment_time', now()->month)
                ->whereYear('appointments.appointment_time', now()->year);
        }

        $rawStats = $query->get();

        $stats = [];
        foreach ($rawStats as $row) {
            $avg = $row->total_appointments > 0
                ? round($row->total_revenue / $row->total_appointments)
                : 0;

            $stats[$row->doctor_id] = [
                'total_appointments' => $row->total_appointments,
                'total_revenue' => $row->total_revenue,
                'average_price' => $avg,
            ];
        }

        return response()->json($stats);
    }
    public function topDoctors()
    {
        $topDoctors = Appointment::select('doctor_id', DB::raw('COUNT(*) as appointments_count'))
            ->groupBy('doctor_id')
            ->orderByDesc('appointments_count')
            ->limit(3)
            ->with(['doctor.user'])
            ->get();

        return response()->json($topDoctors);
    }
    public function PatientStats()
    {
        $total = Patient::count();

        $newThisMonth = Patient::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $popularService = Appointment::select('service_id', DB::raw('count(*) as count'))
            ->groupBy('service_id')
            ->orderByDesc('count')
            ->with('service')
            ->first();

        $averageAppointments = Appointment::select(DB::raw('count(*) / count(distinct patient_id) as avg'))->first()->avg;
        $averageAppointments = round($averageAppointments, 2);

        $repeated = Appointment::select('patient_id')
            ->groupBy('patient_id')
            ->havingRaw('count(*) > 1')
            ->count();

        $repeatPercent = $total ? round(($repeated / $total) * 100, 2) : 0;

        $growthByMonth = Patient::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'total' => $total,
            'new_this_month' => $newThisMonth,
            'popular_service' => $popularService?->service,
            'avg_appointments' => $averageAppointments,
            'repeated_percent' => $repeatPercent,
            'growth_by_month' => $growthByMonth,
        ]);
    }

}
