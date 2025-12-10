<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // Get all appointments
    public function index()
    {
        $appointments = Appointment::with('patient', 'service')
            ->orderBy('appointment_date', 'asc')
            ->paginate(15);
        
        return view('appointments.index', compact('appointments'));
    }

    // Show create form
    public function create()
    {
        $services = Service::all();
        $patients = Patient::all();
        return view('appointments.create', compact('services', 'patients'));
    }

    // Store appointment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date_format:Y-m-d\TH:i|after:now',
            'status' => 'required|in:Scheduled,Ongoing,Completed,Cancelled',
        ]);

        $appointment = Appointment::create([
            ...$validated,
            'modified_by' => auth()->user()->username ?? 'SYSTEM'
        ]);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment created successfully');
    }

    // Show single appointment
    public function show(Appointment $appointment)
    {
        $appointment->load('patient', 'service');
        return view('appointments.show', compact('appointment'));
    }

    // Edit appointment
    public function edit(Appointment $appointment)
    {
        $services = Service::all();
        $patients = Patient::all();
        return view('appointments.edit', compact('appointment', 'services', 'patients'));
    }

    // Update appointment
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:Scheduled,Ongoing,Completed,Cancelled',
        ]);

        $appointment->update([
            ...$validated,
            'modified_by' => auth()->user()->username ?? 'SYSTEM'
        ]);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully');
    }

    // Delete appointment
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully');
    }

    // Get appointments for date range (API)
    public function getByDateRange(Request $request)
    {
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        $appointments = Appointment::with('patient', 'service')
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date')
            ->get();

        return response()->json($appointments);
    }

    // Quick status update (API)
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Scheduled,Ongoing,Completed,Cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
            'modified_by' => auth()->user()->username ?? 'SYSTEM'
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }
}
