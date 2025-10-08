<?php
/**
 * File: Appointment.php
 * Description: Model to handle operations
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Models
 * @author Vivek Singh
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'healthcare_professional_id',
        'appointment_start_time',
        'appointment_end_time',
        'status',
        'notes',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'appointment_start_time' => 'datetime',
            'appointment_end_time' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function healthcareProfessional(): BelongsTo
    {
        return $this->belongsTo(HealthcareProfessional::class);
    }

    // Scopes for common queries
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_start_time', '>', now())
                     ->where('status', 'booked');
    }
}
