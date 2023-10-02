<?php


namespace Lessgap\Models;


use App\Traits\CreatedUpdatedByAdmin;
use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use  CreatedUpdatedByAdmin;

    protected $table = 'event_settings';

    public $timestamps = true;
    protected $fillable = [
        'event_name',
        'data',
        'status'
    ];
    protected $casts = [
        'data' => 'array'
    ];
}
