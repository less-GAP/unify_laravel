<?php


namespace App\Traits;


trait HasContentPath
{
    public static function bootHasContentPath()
    {
        // updating created_by and updated_by when model is created
        static::saving(function ($model) {
            if ($model->content) {
                $contentPath = uniqid();
                \Storage::disk($model->storage)->put($contentPath, $model->content);
                unset($model->content);
                dd($model);
                $model->content_path = $contentPath;
            }
        });
    }

    public function getContentAttribute()
    {
        if ($this->content_path) {
            return \Storage::disk($this->storage)->get($this->content_path);
        }
        return '';
    }
}
