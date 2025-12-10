<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class PatientsController extends Controller
{
    public function index() {
        // Fetch all patients for Livewire component to use
        $patients = Patient::orderBy('created_at', 'desc')->get();
        
        // Pass to view - Livewire components will handle the rest
        return view('patient', compact('patients'));
    }
}
