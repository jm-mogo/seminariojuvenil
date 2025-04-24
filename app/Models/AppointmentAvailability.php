<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class AppointmentAvailability extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d', // Cast to Date object, format Y-m-d
        'time' => 'datetime:H:i', // Cast to Carbon instance, format H:i (use datetime to preserve Carbon)
        // 'registration_id' => 'string', // Already a UUID string, casting might not be strictly necessary
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'time',
        'registration_id',
    ];

    /**
     * Get the registration that booked this slot.
     * Ensure 'registration_id' is the foreign key and 'id' is the owner key on Registration.
     * Adjust keys if they are different.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id');
    }
}
