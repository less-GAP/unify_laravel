<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use App\Traits\HasContentPath;
use App\Traits\HasSlug;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
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

    protected $table = 'posts';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'status',
        'image',
        'meta',
        'created_by',
        'updated_by',
        'data_source_id',
        'source_url',
        'excerpt',
        'content',
        'content_path',
        'country',
        'visit_count'
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
        'image_url',
        'tags'
    ];


    public function getImageUrlAttribute()
    {
        if ($this->image != '') {
            return url($this->image, '', env('APP_ENV') == 'local' ? false : true);
        }
        return '';
    }
}
