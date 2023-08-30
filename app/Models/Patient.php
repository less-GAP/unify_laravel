<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use App\Traits\HasContentPath;
use App\Traits\HasSlug;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'patients';

    public $timestamps = true;

    protected $fillable = [
        'unify_active',
        'unify_status',
        'unify_process',
        'full_name',
        'last_name',
        'first_name',
        'dob',
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
        'unify_deleted',
        'unify_deleted_at',
        'unify_deleted_by',
        'sale_user',
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
        //'product_descr' => 'array',
        'dob' => 'datetime',
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
