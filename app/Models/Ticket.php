<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  

class Ticket extends Model
{
    use HasFactory;

    // Campos asignables en masa
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_to_id',
        'department_id',
        'closed_at',
    ];

    // Casts
    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Relación: Un ticket es creado por un usuario (Solicitante)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un ticket es asignado a un usuario (Agente/Admin)
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    /**
     * Relación: Un ticket pertenece a un departamento
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}