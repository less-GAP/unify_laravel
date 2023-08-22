<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaApplication extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'visa_application';

    public $timestamps = true;

    protected $fillable = [
        'number_of_visa',
        'type_of_visa',
        'date_arrival',
        'entry_port',
        'processing_time',
        'est_delivery_time',
        'total_amount',
        'payment_method',
        'payment_status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status',
        'approval_status',
    ];

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
        'est_delivery_time' => 'datetime',
        'date_arrival' => 'datetime',
//        'password' => 'hashed',
    ];

    protected $appends = [
        'assigned_users'
    ];

    public function applicants()
    {
        return $this->hasMany(VisaApplicationApplicant::class, 'visa_application_id');
    }

    public function comments()
    {
        return $this->hasMany(VisaApplicationComment::class, 'visa_application_id');
    }
    public function history()
    {
        return $this->hasMany(VisaApplicationHistory::class, 'visa_application_id');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'visa_application_assignee', 'visa_application_id', 'user','id','username');
    }
    public function getAssignedUsersAttribute(){
        if($this->assignees->count()){
            return $this->assignees->pluck('username');
        }
        return [];
    }
}
