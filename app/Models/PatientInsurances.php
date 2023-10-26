<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\NotificationPatientData;
use App\Traits\HasRealtimeData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Lessgap\Traits\HasLessgapEvent;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientInsurances extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'patient_insurances';

    public $timestamps = true;

    protected $fillable = [
        'patient_id',
        'insurance_id',
        'coverage',
        'active_date',
        'expired_date',
    ];

    protected $casts = [
        'coverage' => 'array',
    ];


    protected $appends = [

    ];
}
