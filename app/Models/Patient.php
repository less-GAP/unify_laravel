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

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory, LogsActivity, HasRealtimeData, HasLessgapEvent;

    protected $table = 'patients';

    public $timestamps = true;

    protected $fillable = [
        'unify_number',
        'unify_active',
        'unify_status',
        'unify_process',
        'unify_task_status',
        'full_name',
        'last_name',
        'first_name',
        'dob',
        'signature',
        'height',
        'weight',
        'gender',
        'insurance_coverages',
        'email',
        'phone',
        'street',
        'apt',
        'city',
        'state',
        'zip',
        'route',
        'sub_r',
        'supplies',
        'note',
        'unify_data',
        'doctor_id',
        'doctor_status',
        'doctor_comment',
        'service_dates',
        'need_improve',
        'unify_inactive_at',
        'unify_deleted',
        'unify_deleted_at',
        'unify_deleted_by',
        'sale_user',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
    ];


    protected $appends = [
        'seller',
        'is_turn_off',
        'doctor',
        'products'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => request()->input('log_detail', $eventName))
            ->dontSubmitEmptyLogs();
    }

    public function getSellerAttribute()
    {
        return User::select('full_name', 'id')->find($this->attributes['sale_user']);
    }

    public function getIsTurnOffAttribute()
    {
        return in_array($this->unify_status, array(2, 3)) || $this->unify_deleted == 1;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected static function boot() // on create, generate a unique 6-digit number starting from 100000
    {
        parent::boot();

        static::creating(function ($patient) {
            if ($patient->unify_number == '') {
                // Generate a unique 6-digit number starting from 100000
                $nextUnifyNumber = self::getNextUnifyNumber();
                $patient->unify_number = $nextUnifyNumber;
            }
        });
    }

    // Add a method to get the next available unify number
    protected static function getNextUnifyNumber()
    {
        $lastPatient = self::orderBy('unify_number', 'desc')->first();

        if ($lastPatient) {
            $nextUnifyNumber = $lastPatient->unify_number + 1;

            while (self::find($nextUnifyNumber)) {
                $nextUnifyNumber++;
            }
        } else {
            $nextUnifyNumber = 100000; // Start from 100000 if no records exist
        }

        return $nextUnifyNumber;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'sale_user', 'id');
    }

    public function notificationData()
    {
        return [
            'by_user' => \App\Models\User::find($this->update_ted)
        ];
    }

    public function notifiables($types)
    {
        $result = collect([]);
        if (in_array('admin', $types)) {
            $result = $result->merge($this->admins());
        }
        if (in_array('owner', $types)) {
            $result = $result->merge($this->owners());
        }
        return $result;
    }

    public function admins()
    {
        return User::whereHas('roles.permissions', function ($subquery) {
            return $subquery->where('permissions.name', 'Admin')->orWhere('permissions.name', '*');
        })->select(['full_name', 'username', 'id'])->get();
    }

    public function owners()
    {
        if ($this->owner) {
            return [$this->owner->only(['full_name', 'username', 'id'])];
        }
        return [];
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function getDoctorAttribute()
    {
        if ($this->doctor_id != '') {
            return Doctor::where('id', $this->doctor_id)->first();
        }
        return '';
    }

    public function getProductsAttribute()
    {
        return PatientProducts::where('patient_id', $this->id)->get();
    }

}
