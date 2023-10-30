<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailInventories extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'order_detail_inventories';

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'order_detail_id',
        'amount',
        'inventory_id',
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
//        'supplier',
//        'product',
//        'trademark'
    ];


//    public function getSupplierAttribute()
//    {
//        if ($this->supplier_id != '') {
//            return Supplier::where('id', $this->supplier_id)->first();
//        }
//        return '';
//    }
//
//    public function getProductAttribute()
//    {
//        return Product::where('id', $this->product_id)->first();
//    }
//
//
//    public function getTrademarkAttribute()
//    {
//        if ($this->supplier_id != '') {
//            return Trademark::where('id', $this->trademark_id)->first();
//        }
//        return '';
//    }

//    public function getCustomerGroupNameAttribute()
//    {
//        $cus = CustomerGroup::where('id', $this->customer_group)->first();
//        return $cus->name;
//    }
//
//    public function getVideosAttribute()
//    {
//        $vi = [];
//        $videos = SeriesVideo::where('series_id', $this->id)->get();
//        if(!empty($videos)){
//            foreach($videos as $v){
//                $vi[] = $v->video_descr;
//            }
//        }
//        return $vi;
//    }
}
