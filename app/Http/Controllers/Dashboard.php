<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Controller
{
    public function index() {
        $today = Carbon::today();
        
        // Cache dashboard data for 5 minutes to improve performance
        $cacheKey = 'dashboard_' . $today->format('Y-m-d');
        
        $dashboardData = Cache::remember($cacheKey, 300, function () use ($today) {
            return [
                // Fetch count of appointments for today
                'todayAppointmentsCount' => Appointment::whereDate('appointment_date', $today)->count(),
                
                // Fetch count of "Completed" appointments (all time)
                'completedAppointmentsCount' => Appointment::where('status', 'Completed')->count(),
                
                // Fetch total counts for dashboard stats
                'totalPatients' => Patient::count(),
                'totalUsers' => User::count(),
                'totalAppointments' => Appointment::count(),
                
                // Fetch today's appointments with eager loading for performance
                'todayAppointments' => Appointment::with(['patient', 'service'])
                    ->whereDate('appointment_date', $today)
                    ->whereNotIn('status', ['Cancelled'])
                    ->orderBy('appointment_date', 'asc')
                    ->get(),
                
                // Fetch today's cancelled appointments
                'todaysCancelledAppointments' => Appointment::with(['patient', 'service'])
                    ->whereDate('appointment_date', $today)
                    ->where('status', 'Cancelled')
                    ->orderBy('appointment_date', 'asc')
                    ->get(),
                
                // Chart Data 1: Services Distribution (optimized query)
                'servicesData' => DB::table('appointments')
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->select('services.service_name as name', DB::raw('count(appointments.id) as count'))
                    ->groupBy('appointments.service_id', 'services.service_name')
                    ->get(),
                
                // Chart Data 2: Weekly Appointment Trends (optimized single query)
                'weeklyTrends' => $this->getWeeklyTrends($today),
                
                // Chart Data 3: Status breakdown (optimized)
                'statusData' => DB::table('appointments')
                    ->select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get(),
            ];
        });
        
        // Process chart data
        $servicesLabels = [];
        $servicesValues = [];
        foreach ($dashboardData['servicesData'] as $item) {
            $servicesLabels[] = $item->name ?? 'Unknown';
            $servicesValues[] = $item->count;
        }
        
        $weeklyLabels = $dashboardData['weeklyTrends']['labels'];
        $weeklyData = $dashboardData['weeklyTrends']['data'];
        
        // Extract status counts with defaults
        $completedCount = 0;
        $pendingCount = 0;
        $cancelledCount = 0;
        
        foreach ($dashboardData['statusData'] as $item) {
            switch ($item->status) {
                case 'Completed':
                    $completedCount = $item->count;
                    break;
                case 'Pending':
                case 'Scheduled': // Handle both Pending and Scheduled as pending
                    $pendingCount += $item->count;
                    break;
                case 'Cancelled':
                    $cancelledCount = $item->count;
                    break;
            }
        }
        
        return view('dashboard', [
            'todayAppointmentsCount' => $dashboardData['todayAppointmentsCount'],
            'completedAppointmentsCount' => $dashboardData['completedAppointmentsCount'],
            'totalPatients' => $dashboardData['totalPatients'],
            'totalUsers' => $dashboardData['totalUsers'],
            'totalAppointments' => $dashboardData['totalAppointments'],
            'todayAppointments' => $dashboardData['todayAppointments'] ?? collect(),
            'todaysCancelledAppointments' => $dashboardData['todaysCancelledAppointments'] ?? collect(),
            'servicesLabels' => $servicesLabels,
            'servicesValues' => $servicesValues,
            'weeklyLabels' => $weeklyLabels,
            'weeklyData' => $weeklyData,
            'completedCount' => $completedCount,
            'pendingCount' => $pendingCount,
            'cancelledCount' => $cancelledCount,
        ]);
    }

    /**
     * Get weekly appointment trends (last 7 days)
     * Optimized with single database query instead of 7 queries
     */
    private function getWeeklyTrends($today)
    {
        $sixDaysAgo = $today->clone()->subDays(6);
        
        $trends = DB::table('appointments')
            ->selectRaw('DATE(appointment_date) as date, COUNT(*) as count')
            ->whereBetween(DB::raw('DATE(appointment_date)'), [$sixDaysAgo->format('Y-m-d'), $today->format('Y-m-d')])
            ->groupBy(DB::raw('DATE(appointment_date)'))
            ->orderBy(DB::raw('DATE(appointment_date)'))
            ->get()
            ->keyBy('date');
        
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->clone()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('D'); // Mon, Tue, Wed, etc.
            $data[] = $trends->get($dateStr)?->count ?? 0;
        }
        
        return ['labels' => $labels, 'data' => $data];
    }

}