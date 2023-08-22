<?php


namespace App\Traits;


trait CreatedUpdatedByAdmin
{
    public static function bootCreatedUpdatedByAdmin()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (auth('admin')->user()) {
                $model->created_by = auth('admin')->user()->username;
                $model->updated_by = auth('admin')->user()->username;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (auth('admin')->user()) {
                $model->updated_by = auth('admin')->user()->username;
            }
        });
    }
}
