<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventories extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory, LogsActivity;

    protected $table = 'inventories';

    public $timestamps = true;

    protected $fillable = [
        'type',
        'date',
        'created_by',
        'supplier_id',
        'amount',
        'file',
        'status',
        'description',
        'order_id'
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
        'supplier',
        'amount',
        'products'
    ];


    public function getSupplierAttribute()
    {
        if ($this->supplier_id != '') {
            return Supplier::where('id', $this->supplier_id)->first();
        }
        return '';
    }

    public function getAmountAttribute()
    {
        return InventoryDetail::where('inventory_id', $this->id)->sum('amount');
    }

    public function getProductsAttribute()
    {
        return InventoryDetail::where('inventory_id', $this->id)->get();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => request()->input('log_detail',$eventName))
            ->dontSubmitEmptyLogs();
    }

}
