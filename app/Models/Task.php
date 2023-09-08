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

class Task extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

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

    public function patient() : BelongsTo
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
        // 'image_url',
        // 'tags'
    ];


    // public function getImageUrlAttribute()
    // {
    //     if ($this->image != '') {
    //         return url($this->image, '', env('APP_ENV') == 'local' ? false : true);
    //     }
    //     return '';
    // }
}
