<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'task_logs';

    public $timestamps = true;

    protected $fillable = [
        'task_id',
        'action',
        'from_process',
        'to_process',
        'content',
        'created_by',
    ];

    public function task() : BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

}
