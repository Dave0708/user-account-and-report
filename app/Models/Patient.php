<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = []; // Allows all fields to be mass assigned

    // Define relationship to Appointments
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}