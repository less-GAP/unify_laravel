<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventories extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'inventories';

    public $timestamps = true;

    protected $fillable = [
        'type',
        'date',
        'created_by',
        'product_id',
        'supplier_id',
        'trademark_id',
        'amount',
        'expiration_date',
        'used',
        'remaining',
        'status',
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

//    protected $appends = [
//        'image_url'
//    ];
//
//    public function getImageUrlAttribute()
//    {
//        if ($this->logo != '') {
//            return url($this->logo, '', env('APP_ENV') == 'local' ? false : true);
//        }
//        return '';
//    }
}
