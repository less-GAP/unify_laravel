<?php


use App\Builder\EloquentRouter;
use Spatie\QueryBuilder\AllowedFilter;

EloquentRouter::prefix('posts')
    ->handle(\App\Models\Post::class,
        [
            'allowedIncludes'=>['tag_models'],
            'allowedFilters' => [AllowedFilter::custom('tags', new \App\Builder\Filters\SearchTagField,'tags')]
        ]
    )->routes(function () {
        Route::get('/detail/{slug}', \Modules\PostApi\Actions\GetPostDetail::class . '@handle');
    });
