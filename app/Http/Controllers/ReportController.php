<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $totalPatients = Patient::count();
        $totalAppointments = Appointment::count();
        $completedAppointments = Appointment::where('status', 'Completed')->count();
        $cancelledAppointments = Appointment::where('status', 'Cancelled')->count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();

        // Daily appointments for chart
        $dailyData = DB::table('appointments')
            ->select(DB::raw('DATE(appointment_date) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->limit(30)
            ->get();

        $dates = $dailyData->pluck('date');
        $totals = $dailyData->pluck('total');

        // Appointment status distribution
        $statusData = DB::table('appointments')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $statusLabels = $statusData->pluck('status');
        $statusCounts = $statusData->pluck('total');

        // Service statistics
        $serviceData = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.service_name', DB::raw('count(*) as total'))
            ->groupBy('services.service_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $serviceNames = $serviceData->pluck('service_name');
        $serviceCounts = $serviceData->pluck('total');

        return view('reports.index', compact(
            'totalPatients',
            'totalAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'todayAppointments',
            'dates',
            'totals',
            'statusLabels',
            'statusCounts',
            'serviceNames',
            'serviceCounts'
        ));
    }

    // Patient report
    public function patients(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('mobile_number', 'like', "%$search%");
        }

        $patients = $query->orderBy('last_name')->paginate(20);
        
        return view('reports.patients', compact('patients'));
    }

    // Appointment report
    public function appointments(Request $request)
    {
        $query = Appointment::with('patient', 'service');

        if ($request->filled('start_date')) {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(20);
        $services = Service::all();

        return view('reports.appointments', compact('appointments', 'services'));
    }

    // Export appointments to CSV
    public function exportAppointments(Request $request)
    {
        $query = Appointment::with('patient', 'service');

        if ($request->filled('start_date')) {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }

        $appointments = $query->orderBy('appointment_date')->get();

        $filename = 'appointments_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];

        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Time', 'Patient Name', 'Service', 'Status', 'Modified By']);

            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->appointment_date->format('Y-m-d'),
                    $appointment->appointment_date->format('H:i'),
                    $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                    $appointment->service->service_name,
                    $appointment->status,
                    $appointment->modified_by
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export patients to CSV
    public function exportPatients()
    {
        $patients = Patient::orderBy('last_name')->get();

        $filename = 'patients_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];

        $callback = function() use ($patients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['First Name', 'Last Name', 'Mobile', 'Email', 'Birth Date', 'Gender', 'Civil Status', 'Address']);

            foreach ($patients as $patient) {
                fputcsv($file, [
                    $patient->first_name,
                    $patient->last_name,
                    $patient->mobile_number,
                    $patient->email_address ?? '',
                    $patient->birth_date ?? '',
                    $patient->gender ?? '',
                    $patient->civil_status ?? '',
                    $patient->home_address ?? ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}