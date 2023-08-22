<?php

namespace Modules\PostApi;


use App\Providers\AbstractModuleProvider;
use ProtoneMedia\Splade\Facades\Splade;

class ServiceProvider extends AbstractModuleProvider
{
    protected $middleware = [];
    protected $routePrefix = '/post-api';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
