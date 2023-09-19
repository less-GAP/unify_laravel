<?php

namespace App\Models;

use App\Traits\CreatedUpdatedByAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Doctor extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory, CreatedUpdatedByAdmin, LogsActivity;

    protected $table = 'doctors';

    public $timestamps = true;

    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'certificate',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'note',
        'status',
        'deleted',
        'deleted_at',
        'deleted_by',
        'updated_by',
        'created_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => request()->input('log_detail',$eventName))
            ->dontSubmitEmptyLogs();
    }
    
    /**
     * Get the user that owns the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'doctor_id', 'id');
    }
}
