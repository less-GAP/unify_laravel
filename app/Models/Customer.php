<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory, HasApiTokens;

    protected $table = 'customers';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'email_verified_at',
        'status',
        'customer_group',
        'social_auth_id',
        'social_auth_type',
        'username',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
//        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
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
        'customer_group_name',
    ];

    public function getCustomerGroupNameAttribute()
    {
        $cus = CustomerGroup::where('id', $this->customer_group)->first();
        if (!isset($cus->name)) {
            return 'N/A';
        }
        return $cus->name;
    }

//    public function getImageUrlAttribute()
//    {
//        if ($this->image != '') {
//            return url('upload/' . $this->image, '', env('APP_ENV') == 'local' ? false : true);
//        }
//        return '';
//    }
}
