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
    use CreatedUpdatedByAdmin, HasSlug, HasTags;

    public $storage = 'post-content';

    public $slugBy = 'title';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'patients';

    public $timestamps = true;

    protected $fillable = [
        'unify_id',
        'unify_name',
        'unify_number',
        'unify_active',
        'unify_status',
        'sale_user',
        'doctor_id',
        'unify_created_at',
        'unify_process',
        'unify_deleted',
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
