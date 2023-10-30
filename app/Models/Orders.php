<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'orders';

    public $timestamps = true;

    protected $fillable = [
        'patient_id',
        'shipping_id',
        'delivery_date',
        'phone',
        'address',
        'apt',
        'city',
        'state',
        'zip',
        'route',
        'sub_r',
        'status',
        'street',
        'shipper_name',
        'shipper_phone',
        'shipper_email',
        'created_by'
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
        'patient',
        'shipper',
        'products'
    ];

    public function getPatientAttribute()
    {
        return Patient::where('id', $this->patient_id)->first();
    }

    public function getShipperAttribute()
    {
        return User::where('id', $this->shipping_id)->first();
    }

    public function getProductsAttribute()
    {
        return OrderDetails::where('order_id', $this->id)->get();
    }
//
//    public function getPaymentNameAttribute()
//    {
//        $data = PaymentMethod::where('id', $this->payment_id)->first();
//        return $data->name;
//    }
//
//    public function getCountryNameAttribute()
//    {
//        $data = Countries::where('code', $this->s_country)->first();
//        return $data->name;
//    }
//
//    public function getProvinceNameAttribute()
//    {
//        $data = Provinces::where('code', $this->s_province)
//            ->where('country_code', $this->s_country)
//            ->first();
//        return $data->name;
//    }
//
//    public function getDistrictNameAttribute()
//    {
//        $data = Districts::where('code', $this->s_district)
//            ->where('province_code', $this->s_province)
//            ->where('country_code', $this->s_country)
//            ->first();
//        return $data->name;
//    }
//
//    public function getWardNameAttribute()
//    {
//        $data = Wards::where('code', $this->s_ward)
//            ->where('province_code', $this->s_province)
//            ->where('country_code', $this->s_country)
//            ->where('district_code', $this->s_district)
//            ->first();
//        return $data->name;
//    }


}
