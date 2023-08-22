<?php


namespace App\Traits;


trait HasWebsiteFile
{
    public static function bootHasWebsiteFile()
    {
        // updating created_by and updated_by when model is created
        static::saved(function ($model) {
            $data = $model->toArray();
            $data['app_url'] = "https://" . $model->domain;
            $data['api_url'] = env('APP_URL') . '/post-api';
            $data['pages'] = [];
            foreach ($model->pages as $page) {
                $data['pages'][$page['slug']] = $page;
            }
            \Storage::disk('domains')->put($model->domain . '/config.json', json_encode($data));
        });

        // updating updated_by when model is updated
//        static::updating(function ($model) {
//            if ($model->isDirty($model->slugBy)) {
//                $model->slug = \Str::slug($model->{$model->slugBy}).'-'.uniqid();
//            }
//        });
    }
}
