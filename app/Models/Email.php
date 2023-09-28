<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory,CreatedUpdatedByAdmin;

    protected $table = 'emails';

    public $timestamps = true;

    protected $fillable = [
        'template_id',
        'status',
        'email_title',
        'email_to',
        'email_cc',
        'email_bcc',
        'email_content',
        'email_data',
        'sent_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_data' => 'array',
        'email_cc' => 'array',
        'email_bcc' => 'array',
        'sent_at'=>'datetime'
    ];

}
