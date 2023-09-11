<?php

use App\Builder\EloquentRouter;
use App\Models\EmailTemplate;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Actions\GetUserInfoAction;
use Modules\Admin\Actions\User\DeleteUserAction;
use Modules\Admin\Actions\Customer\DeleteCustomerAction;
use Modules\Admin\Middleware\AdminIsAuthenticated;
use Spatie\QueryBuilder\AllowedFilter;

Route::get('/', function () {
    return 'admin api';
});
Route::post('auth/login', \Modules\Admin\Actions\Auth\PostLoginAction::class . '@handle');

Route::middleware([AdminIsAuthenticated::class])->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::get('userInfo', GetUserInfoAction::class . '@handle');
    });
    //    Route::prefix('/user')->group(function () {
    //        Route::get('list', \Modules\Admin\Actions\User\GetUserListAction::class . '@handle');
    //        Route::post('activeList', \Modules\Admin\Actions\User\PostImageAction::class . '@handle');
    //        Route::post('', \Modules\Admin\Actions\User\PostUserAction::class . '@handle');
    //        Route::delete('{id}', DeleteUserAction::class . '@handle');
    //    });
    EloquentRouter::prefix('user')
        ->routes(function () {
            Route::get('/options', \Modules\Admin\Actions\User\GetUserOptionsAction::class . '@handle');
            Route::get('list', \Modules\Admin\Actions\User\GetUserListAction::class . '@handle');
            Route::post('{id?}', \Modules\Admin\Actions\User\PostUserAction::class . '@handle');
            Route::delete('{id}', \Modules\Admin\Actions\User\DeleteUserAction::class . '@handle');
        })
        ->handle(
            \App\Models\User::class,
            [
                'allowedIncludes' => ['roles', 'roles.permissions', 'permissions'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,username')
                    , AllowedFilter::custom('roles.name', new \App\Builder\Filters\SearchRelationShip, 'roles.name')
                ]
            ]
        );

    EloquentRouter::prefix('task')
        ->handle(
            \App\Models\Task::class,
            [
                // 'allowedIncludes' => ['assignees', 'users'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('patient_id'),
                    AllowedFilter::exact('is_completed'),
                ]
            ]
        );
    // EloquentRouter::prefix('visa-application')
    //     ->handle(\App\Models\VisaApplication::class,
    //         [
    //             'allowedIncludes' => ['applicants', 'history', 'assignees'],
    //             'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'contact_name,contact_email,mobile_phone')]
    //         ]
    //     )->routes(function () {
    //         Route::post('/', \Modules\Admin\Actions\VisaApplication\PostIndexAction::class . '@handle');
    //         Route::post('/status', \Modules\Admin\Actions\VisaApplication\PostStatusAction::class . '@handle');
    //         Route::post('/assign', \Modules\Admin\Actions\VisaApplication\PostAssignAction::class . '@handle');
    //     });
    EloquentRouter::prefix('post')
        ->handle(
            \App\Models\Post::class,
            [

                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'title')]
            ]
        )->routes(function () {
        });

    EloquentRouter::prefix('patient')
        ->handle(
            \App\Models\Patient::class,
            [
                'allowedIncludes' => ['tasks'],
                'allowedSorts' => ['id', 'name', 'updated_at'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,phone,email'), AllowedFilter::custom('phone', new \App\Builder\Filters\SearchLikeMultipleField, 'phone')
                ]
            ]
        )->routes(function () {
            Route::get('/list', \Modules\Admin\Actions\Patient\GetListAction::class . '@handle');
        });

    EloquentRouter::prefix('websites')
        ->handle(
            \App\Models\Website::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name,domain')]
            ]
        )->routes(function () {
        });
    EloquentRouter::prefix('themes')
        ->routes(function () {
            Route::get('options', \Modules\Admin\Actions\Themes\GetOptionsAction::class . '@handle');
            Route::post('{id?}', \Modules\Admin\Actions\Themes\PostCreateUpdateAction::class . '@handle');
            Route::put('{id?}', \Modules\Admin\Actions\Themes\PostCreateUpdateAction::class . '@handle');
        })
        ->handle(
            \App\Models\Theme::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name')]
            ]
        );

    EloquentRouter::prefix('file')
        ->handle(
            \App\Models\File::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'file_name')]
            ]
        )->routes(function () {
            Route::post('/Upload', \Modules\Admin\Actions\File\PostUploadAction::class . '@handle');
            Route::post('/Info', \Modules\Admin\Actions\File\PostInfoAction::class . '@handle');
        });

    EloquentRouter::prefix('email-template')
        ->handle(
            \App\Models\EmailTemplate::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'email_title,email_content')]
            ]
        );


    Route::prefix('/config')->group(function () {
        Route::get('/', \Modules\Admin\Actions\Config\GetListAction::class . '@handle');
        Route::post('/', \Modules\Admin\Actions\Config\PostAction::class . '@handle');
        Route::post('/testSmtp', \Modules\Admin\Actions\Config\PostTestSmtpAction::class . '@handle');
    });
    Route::prefix('/customer')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Customer\GetListAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Customer\PostActiveListAction::class . '@handle');
        Route::delete('{id}', DeleteCustomerAction::class . '@handle');
    });

    Route::prefix('/customer-group')->group(function () {
        Route::get('list', \Modules\Admin\Actions\CustomerGroup\GetListAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\CustomerGroup\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\CustomerGroup\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\CustomerGroup\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\CustomerGroup\DeleteAction::class . '@handle');
    });

    Route::prefix('/countries')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Countries\GetListAction::class . '@handle');
        Route::get('all', \Modules\Admin\Actions\Countries\GetAllAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Countries\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Countries\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Countries\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\Countries\DeleteAction::class . '@handle');
    });

    EloquentRouter::prefix('master-data')
        ->handle(
            \App\Models\MasterData::class,
            []
        )->routes(function () {
            Route::get('{listKey}/options', \Modules\Admin\Actions\MasterData\GetOptionsAction::class . '@handle');
        });
    EloquentRouter::prefix('permissions')
        ->handle(\App\Models\Permission::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('status'),
                ]
            ]
        );
    EloquentRouter::prefix('roles')
        ->handle(\App\Models\Role::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name')

                ]
            ]
        );
});
