<?php


namespace App\Traits;


use App\Services\RealtimeService;

trait HasRealtimeData
{
    public static function bootHasRealtimeData()
    {
        // updating created_by and updated_by when model is created
        static::saved(function ($model) {
            app(RealtimeService::class)->updateTableValue('versions', $model->getTable());
        });
        static::created(function ($model) {
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_count', static::count());
        });
        static::deleted(function ($model) {
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_count', static::count());
        });

        // updating updated_by when model is updated
//        static::updating(function ($model) {
//            if ($model->isDirty($model->slugBy)) {
//                $model->slug = \Str::slug($model->{$model->slugBy}).'-'.uniqid();
//            }
//        });
    }
}
