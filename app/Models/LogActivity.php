<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Patient;
use App\Models\Task;
use App\Models\Doctor;
use App\Models\User;

class LogActivity extends \Spatie\Activitylog\Models\Activity
{
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'causer_id');
    }

    public function getNameLogAttribute()
    {
        $nameLog = '';
        if ( $this->attributes['subject_type'] == 'App\Models\Patient' ){
            $nameLog = Patient::find($this->attributes['subject_id'])->full_name;
        } else if ( $this->attributes['subject_type'] == 'App\Models\Task' ){
            $nameLog = Task::find($this->attributes['subject_id'])->name;
        } else if ( $this->attributes['subject_type'] == 'App\Models\Doctor' ){
            $nameLog = Doctor::find($this->attributes['subject_id'])->full_name;
        }

        return $nameLog;
    }
    public function getCauserAttribute()
    {
        $user = User::find($this->attributes['causer_id']);
        return $user;
    }

    protected $appends = [
        'name_log',
        'causer'
    ];
}
