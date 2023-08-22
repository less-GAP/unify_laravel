<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use App\Traits\HasSlug;
use App\Traits\HasWebsiteFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use CreatedUpdatedByAdmin,HasWebsiteFile;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'websites';

    public $timestamps = true;

    protected $fillable = [
        'domain',
        'icon',
        'logo',
        'image',
        'site_title',
        'theme',
        'pages',
        'description',
        'created_by',
        'updated_by',
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
        'pages' => 'array',
    ];

    protected $appends = [
        'image_url',
        'logo_url'
    ];
    public function getImageUrlAttribute()
    {
        if ($this->image != '') {
            return url($this->image, '', env('APP_ENV') == 'local' ? false : true);
        }
        return '';
    }
    public function getLogoUrlAttribute()
    {
        if ($this->logo != '') {
            return url($this->logo, '', env('APP_ENV') == 'local' ? false : true);
        }
        return '';
    }
    public function getIconUrlAttribute()
    {
        if ($this->icon != '') {
            return url($this->icon, '', env('APP_ENV') == 'local' ? false : true);
        }
        return '';
    }
}
