<?php


namespace App\Models;


class LogActivity extends \Spatie\Activitylog\Models\Activity
{
    public function user(){
        return $this->belongsTo(User::class,'causer_id');
    }
}
