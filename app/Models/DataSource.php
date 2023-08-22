<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedByAdmin;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    use CreatedUpdatedByAdmin;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $table = 'data_source';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'url',
        'rule_detect_category_link',
        'rule_detect_post_link',
        'rule_detect_post_title',
        'rule_detect_post_excerpt',
        'rule_detect_post_content',
        'rule_detect_post_image',
        'country',
        'status',
        'latest_crawl_time',
        'craw_frequently_hours',
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
        'latest_crawl_time' => 'datetime',
    ];

    protected $appends = [
    ];
    public function source_urls(){
        return $this->hasMany(DataSourceUrl::class,'data_source_id');
    }
    public function post_urls(){
        return $this->hasMany(CrawlPostUrl::class,'data_source_id');
    }
}
