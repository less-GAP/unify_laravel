<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_source', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('url')->nullable();
            $table->string('rule_detect_category_link')->default('//nav//li//a/@href');
            $table->string('rule_detect_post_link')->default('//h3/a//@href');
            $table->string('rule_detect_post_title')->default('//title//text()');
            $table->string('rule_detect_post_excerpt')->default('//meta[@name="description"][1]//@content');
            $table->string('rule_detect_post_keywords')->default('//meta[@name="keywords"][1]//@content');
            $table->string('rule_detect_post_content')->default('//p/following-sibling::p/following-sibling::p/parent::article[1]');
            $table->string('rule_detect_post_image')->default('//meta[@property="og:image"][1]//@content');
            $table->string('rule_detect_post_tags')->default('//meta[@property="article:tag"][1]//@content');
            $table->string('country')->nullable();
            $table->string('default_post_status')->default('publish');
            $table->integer('craw_frequently_hours')->default(5);
            $table->dateTime('latest_crawl_time')->nullable();
            $table->string('status')->index()->default('active');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        Schema::create('data_source_url', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique()->nullable();
            $table->integer('craw_frequently_hours')->default(5);
            $table->dateTime('latest_crawl_time')->nullable();
        });
        Schema::create('crawl_post_url', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->integer('data_source_id');
            $table->integer('data_source_url_id')->nullable();
            $table->string('status')->default('init');
            $table->longText('content')->nullable();
            $table->dateTime('crawl_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::dropIfExists('data_source');
        Schema::dropIfExists('data_source_url');
        Schema::dropIfExists('crawl_post_url');
    }
};
