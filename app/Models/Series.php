<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'series';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'customer_group',
        'status'
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
        'customer_group_name',
        'videos'
    ];

    public function getCustomerGroupNameAttribute()
    {
        $cus = CustomerGroup::where('id', $this->customer_group)->first();
        return $cus->name;
    }

    public function getVideosAttribute()
    {
        $vi = [];
        $videos = SeriesVideo::where('series_id', $this->id)->get();
        if(!empty($videos)){
            foreach($videos as $v){
                $vi[] = $v->video_descr;
            }
        }
        return $vi;
    }
}
