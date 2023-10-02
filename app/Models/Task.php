<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use App\Traits\HasContentPath;
use App\Traits\HasSlug;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory, LogsActivity;

    protected $table = 'tasks';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'deadline_at',
        'is_completed',
        'completed_at',
        'task_process',
        'created_by',
        'assignees',
        'patient_id',
        'task_tag_id',
        'task_priority_id',
        'deleted',
        'deleted_at',
        'deleted_by',
    ];
    
    protected function getAssigneesAttribute($value)
    {
        if(!$value) return null;
        $value = json_decode($value) ?: '[]';
        $array_assignees = [];
        foreach ($value as $key => $assignee) {
            $user = User::findOrFail($assignee);
            $user->value = $user->id;
            $user->label = $user->full_name;
            $array_assignees[] = $user;
        }
        return $array_assignees;
    }

    protected function getPatientAttribute($value){
        if(!$this->attributes['patient_id']) return null;
        $patient = Patient::select('full_name', 'id', 'unify_number', 'sale_user')->findOrFail($this->attributes['patient_id']);
        if($patient->is_turn_off == 1) return null;
        return $patient;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => request()->input('log_detail',$eventName))
            ->dontSubmitEmptyLogs();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //        'password',
        //        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'product_descr' => 'array',
        // 'dob' => 'date',
    ];

    protected $appends = [
        'patient',
    ];
}
