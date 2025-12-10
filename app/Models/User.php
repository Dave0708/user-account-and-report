<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'contact',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the role information for the user.
     */
    public function roleInfo()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 1;
    }

    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->role === 2;
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === 3;
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'user_id';
    }
}