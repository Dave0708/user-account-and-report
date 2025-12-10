<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\HealthHistory;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // List all patients
    public function index()
    {
        $patients = Patient::orderBy('last_name', 'asc')
            ->paginate(20);
        
        return view('patients.index', compact('patients'));
    }

    // Show create form
    public function create()
    {
        return view('patients.create');
    }

    // Store patient
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'mobile_number' => 'required|string|max:20|unique:patients',
            'email_address' => 'nullable|email|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'occupation' => 'nullable|string|max:100',
            'civil_status' => 'nullable|string|max:50',
            'home_address' => 'nullable|string',
            'office_address' => 'nullable|string',
            'home_number' => 'nullable|string|max:20',
            'office_number' => 'nullable|string|max:20',
        ]);

        $patient = Patient::create([
            ...$validated,
            'modified_by' => auth()->user()->username ?? 'SYSTEM'
        ]);

        return redirect()->route('patients.show', $patient)->with('success', 'Patient created successfully');
    }

    // Show patient details
    public function show(Patient $patient)
    {
        $patient->load('appointments', 'healthHistory');
        return view('patients.show', compact('patient'));
    }

    // Edit patient
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    // Update patient
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'mobile_number' => 'required|string|max:20|unique:patients,mobile_number,' . $patient->id,
            'email_address' => 'nullable|email|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'occupation' => 'nullable|string|max:100',
            'civil_status' => 'nullable|string|max:50',
            'home_address' => 'nullable|string',
            'office_address' => 'nullable|string',
            'home_number' => 'nullable|string|max:20',
            'office_number' => 'nullable|string|max:20',
        ]);

        $patient->update([
            ...$validated,
            'modified_by' => auth()->user()->username ?? 'SYSTEM'
        ]);

        return redirect()->route('patients.show', $patient)->with('success', 'Patient updated successfully');
    }

    // Delete patient
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully');
    }

    // Search patients (API)
    public function search(Request $request)
    {
        $query = $request->query('q');
        
        $patients = Patient::where('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->orWhere('mobile_number', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json($patients);
    }
}
