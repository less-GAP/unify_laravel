<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'products';

    public $timestamps = true;

    protected $fillable = [
        'image',
        'name',
        'type',
        'slug',
        'short_description',
        'description',
        'status',
        'title',
        'meta_description',
        'meta_keyword',
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
//        'email_verified_at' => 'datetime',
//        'password' => 'hashed',
    ];

    protected $appends = [
        'image_url',
        'inventory'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image != '') {
            return url($this->image, '', env('APP_ENV') == 'local' ? false : true);
        }
        return '';
    }

    public function images()
    {
        return $this->belongsToMany(File::class, 'product_images', 'product_id', 'file_id');
    }

    public function getInventoryAttribute()
    {
        return InventoryDetail::where('product_id', $this->id)->where('type', 'in')->sum('remaining');
    }
}
