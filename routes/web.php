<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Login;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NoteController;

// --- Public / Guest Routes ---
Route::middleware(['guest'])->group(function() {
    Route::get('/', [Login::class, 'index'])->name('login');
    Route::post('/login', [Login::class, 'login']);
});

// --- Protected Routes ---
Route::middleware(['isAdmin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
    
    // Appointments - Use Calendar (Livewire)
    Route::get('/appointment', function() {
        return view('appointment');
    })->name('appointment');
    
    // Patients (Uses Livewire components)
    Route::get('/patient-records', [PatientsController::class, 'index'])->name('patient-records');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Notes Management
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // System Utilities
    Route::get('/admin/backup-database', [BackupController::class, 'downloadBackup'])->name('admin.db.backup');
    Route::post('/logout', [Login::class, 'logout'])->name('logout');
});