<?php


namespace App\Traits;



use Lessgap\Services\RealtimeService;

trait HasRealtimeData
{
    public static function bootHasRealtimeData()
    {
        static::saved(function ($model) {
            app(RealtimeService::class)->updateTableValue('versions', $model->getTable());
            
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_waiting_count', static::where('unify_process', 0)->count());
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_check_count', static::where('unify_process', 1)->count());
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_active_count', static::where(['unify_process' => 2, 'unify_deleted' => 0])->count());
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_inactive_count', static::where('unify_deleted', 1)->count());
        });
        static::created(function ($model) {
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_count', static::count());
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_waiting_count', static::where('unify_process', 0)->count());
        });
        static::deleted(function ($model) {
            app(RealtimeService::class)->updateTableValue('statistics', $model->getTable() . '_count', static::count());
        });
    }
}
