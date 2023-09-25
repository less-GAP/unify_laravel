<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogActivity extends \Spatie\Activitylog\Models\Activity
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'causer_id');
    }
}
