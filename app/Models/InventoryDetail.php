<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'inventory_detail';

    public $timestamps = true;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'trademark_id',
        'amount',
        'expiration_date',
        'used',
        'remaining',
        'order_id',
        'type',
        'supplier_id'
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
        'product',
        'trademark'
    ];


    public function getProductAttribute()
    {
        if ($this->product_id != '') {
            return Product::where('id', $this->product_id)->first();
        }
        return '';
    }

    public function getTrademarkAttribute()
    {
        if ($this->trademark_id != '') {
            return Trademark::where('id', $this->trademark_id)->first();
        }
        return '';
    }
}
