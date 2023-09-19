<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory,LogsActivity;

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
        'products',
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
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => request()->input('log_detail'))
            ->dontSubmitEmptyLogs();
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected static function boot() // on create, generate a unique 6-digit number starting from 100000
    {
        parent::boot();

        static::creating(function ($patient) {
            // Generate a unique 6-digit number starting from 100000
            $nextUnifyNumber = self::getNextUnifyNumber();
            $patient->unify_number = $nextUnifyNumber;
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

    // public function getImageUrlAttribute()
    // {
    //     if ($this->image != '') {
    //         return url($this->image, '', env('APP_ENV') == 'local' ? false : true);
    //     }
    //     return '';
    // }
}
