<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- Added this to fix the error

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'appointment_date',
        'status',
        'service_id',
        'patient_id',
        'modified_by',
    ];

    // Define relationship to Service
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Define relationship to Patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}