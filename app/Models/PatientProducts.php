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

class PatientProducts extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'patient_products';

    public $timestamps = true;

    protected $fillable = [
        'patient_id',
        'delivery_date',
        'amount',
        'product_id',
        'product',
        'delivery_value',
        'delivery_type',
        'delivery_option'
    ];

    protected $casts = [
        'product' => 'array',
        'delivery_option' => 'array',
    ];


    protected $appends = [

    ];
}
