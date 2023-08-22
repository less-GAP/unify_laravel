<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use App\Traits\HasContentPath;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlPostUrl extends Model
{
    use CreatedUpdatedByAdmin;
    public $storage = 'crawled-content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'crawl_post_url';

    public $timestamps = true;

    protected $fillable = [
        'url',
        'data_source_id',
        'data_source_url_id',
        'status',
        'content',
        'content_path',
        'crawl_time',
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
        //'product_descr' => 'array',
        'crawl_time' => 'datetime',
    ];

    protected $appends = [
    ];

    public function source()
    {
        return $this->belongsTo(DataSource::class, 'data_source_id');
    }
}
