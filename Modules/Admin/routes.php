<?php

use App\Builder\EloquentRouter;
use App\Models\EmailTemplate;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Lessgap\Models\EventSetting;
use Modules\Admin\Actions\GetUserInfoAction;
use Modules\Admin\Actions\User\DeleteUserAction;
use Modules\Admin\Actions\Customer\DeleteCustomerAction;
use Modules\Admin\Middleware\AdminIsAuthenticated;
use Spatie\QueryBuilder\AllowedFilter;

Route::get('/', function () {
    return 'admin api';
});
Route::post('auth/login', \Modules\Admin\Actions\Auth\PostLoginAction::class . '@handle');
Route::post('submit-patient', \Modules\Admin\Actions\SubmitPatientAction::class . '@handle');

EloquentRouter::prefix('master-data')
    ->handle(
        \App\Models\MasterData::class,
        []
    )->routes(function () {
        Route::get('{listKey}/options', \Modules\Admin\Actions\MasterData\GetOptionsAction::class . '@handle');
    });


Route::prefix('/config')->group(function () {
    Route::get('/', \Modules\Admin\Actions\Config\GetListAction::class . '@handle');
});

Route::middleware([AdminIsAuthenticated::class])->group(function () {
    Route::prefix('/config')->group(function () {
        Route::post('/', \Modules\Admin\Actions\Config\PostAction::class . '@handle');
        Route::post('/testSmtp', \Modules\Admin\Actions\Config\PostTestSmtpAction::class . '@handle');
    });

    Route::prefix('/auth')->group(function () {
        Route::get('userInfo', GetUserInfoAction::class . '@handle');
    });
    EloquentRouter::prefix('user')
        ->handle(
            \App\Models\User::class,
            [
                'allowedIncludes' => ['roles', 'roles.permissions', 'permissions', 'logs'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,username'), AllowedFilter::custom('roles.name', new \App\Builder\Filters\SearchRelationShip, 'roles.name')
                ]
            ]
        )
        ->routes(function () {
            Route::post('/', \Modules\Admin\Actions\User\PostAction::class . '@handle');
            Route::get('/options', \Modules\Admin\Actions\User\GetUserOptionsAction::class . '@handle');
            Route::get('list', \Modules\Admin\Actions\User\GetUserListAction::class . '@handle');
            Route::get('all', \Modules\Admin\Actions\User\GetAllAction::class . '@handle');
            //Route::post('{id?}', \Modules\Admin\Actions\User\PostUserAction::class . '@handle');
            // Route::delete('{id}', \Modules\Admin\Actions\User\DeleteUserAction::class . '@handle');
        });
    EloquentRouter::prefix('doctor')
        ->handle(
            \App\Models\Doctor::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name'),
                ]
            ]
        )->routes(
            function () {
                Route::get('list', \Modules\Admin\Actions\Doctor\GetListAction::class . '@handle');
                Route::post('/', \Modules\Admin\Actions\Doctor\PostAction::class . '@handle');
            }
        );
    EloquentRouter::prefix('logs')
        ->handle(
            \App\Models\LogActivity::class,
            [
                // 'allowedIncludes' => ['users'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'description'),
                ]
            ]
        )->routes(
            function () {
                // Route::get('list', \Modules\Admin\Actions\Doctor\GetListAction::class . '@handle');
            }
        );
    EloquentRouter::prefix('task')
        ->handle(
            \App\Models\Task::class,
            [
                // 'allowedIncludes' => ['assignees', 'users'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('patient_id'),
                    AllowedFilter::exact('task_process'),
                    AllowedFilter::exact('deleted'),
                    AllowedFilter::exact('is_completed'),
                    AllowedFilter::exact('from_date'),
                    AllowedFilter::exact('to_date'),
                ]
            ]
        )->routes(
            function () {
                Route::get('list', \Modules\Admin\Actions\Task\GetListAction::class . '@handle');
                Route::get('all', \Modules\Admin\Actions\Task\GetAllAction::class . '@handle');
            }
        );
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
                'autoPermission' => true,
                'allowedIncludes' => ['tasks', 'doctor'],
                'allowedSorts' => ['id', 'name', 'updated_at'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,phone,email'),
                    AllowedFilter::custom('phone', new \App\Builder\Filters\SearchLikeMultipleField, 'phone'),
                    AllowedFilter::custom('unify_process', new \App\Builder\Filters\SearchLikeMultipleField, 'unify_process'),
                ]
            ]
        )->routes(function () {
            Route::post('/', \Modules\Admin\Actions\Patient\PostAction::class . '@handle');
            Route::get('/list', \Modules\Admin\Actions\Patient\GetListAction::class . '@handle');
            Route::get('/all', \Modules\Admin\Actions\Patient\GetAllAction::class . '@handle');
            Route::get('/{id}', \Modules\Admin\Actions\Patient\GetDetailAction::class . '@handle');
        });
    EloquentRouter::prefix('activity')
        ->handle(
            \App\Models\LogActivity::class,
            [
                'autoPermission' => true,
                'allowedIncludes' => ['user'],
                'allowedSorts' => ['id'],
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'description'),
                    AllowedFilter::exact('causer_id'),
                    AllowedFilter::exact('subject_id'),
                    AllowedFilter::exact('subject_type')
                ]
            ]
        )->routes(function () {
        });


    EloquentRouter::prefix('file')
        ->handle(
            \App\Models\File::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'file_name')]
            ]
        )->routes(function () {
            Route::get('list', \Modules\Admin\Actions\File\GetListAction::class . '@handle');
            Route::post('/Upload', \Modules\Admin\Actions\File\PostUploadAction::class . '@handle');
            Route::post('/Info', \Modules\Admin\Actions\File\PostInfoAction::class . '@handle');
        });

    EloquentRouter::prefix('email')
        ->handle(
            \App\Models\Email::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'email_title,email_to')]
            ]
        );
    EloquentRouter::prefix('email-template')
        ->handle(
            \App\Models\EmailTemplate::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'email_title,email_content')]
            ]
        );

    EloquentRouter::prefix('event-setting')
        ->handle(
            EventSetting::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'event_name')]
            ]
        );

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

    Route::prefix('/faqs')->group(function () {
        Route::get('list', \Modules\Admin\Actions\FAQs\GetListAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\FAQs\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\FAQs\GetDetailAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\FAQs\DeleteAction::class . '@handle');
    });

    Route::prefix('/countries')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Countries\GetListAction::class . '@handle');
        Route::get('all', \Modules\Admin\Actions\Countries\GetAllAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Countries\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Countries\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Countries\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\Countries\DeleteAction::class . '@handle');
    });

    EloquentRouter::prefix('permissions')
        ->handle(
            \App\Models\Permission::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('status'),
                ]
            ]
        );
    EloquentRouter::prefix('roles')
        ->handle(
            \App\Models\Role::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name')

                ]
            ]
        );
    Route::get('notifications', \Modules\Admin\Actions\Notification\GetNotifications::class . '@handle');
    Route::post('notification/read', \Modules\Admin\Actions\Notification\ReadAction::class . '@handle');

    EloquentRouter::prefix('product')
        ->handle(
            \App\Models\Product::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('status'),
                ]
            ]
        )->routes(function () {
            Route::post('/', \Modules\Admin\Actions\Product\PostAction::class . '@handle');
            Route::post('/activeList', \Modules\Admin\Actions\Product\PostActiveListAction::class . '@handle');
        });

    EloquentRouter::prefix('supplier')
        ->handle(
            \App\Models\Supplier::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('status'),
                ]
            ]
        )->routes(function () {
            Route::post('/', \Modules\Admin\Actions\Supplier\PostAction::class . '@handle');
            Route::post('/activeList', \Modules\Admin\Actions\Supplier\PostActiveListAction::class . '@handle');
        });

    EloquentRouter::prefix('trademark')
        ->handle(
            \App\Models\Trademark::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('status'),
                ]
            ]
        )->routes(function () {
            Route::post('/', \Modules\Admin\Actions\Trademark\PostAction::class . '@handle');
            Route::post('/activeList', \Modules\Admin\Actions\Trademark\PostActiveListAction::class . '@handle');
        });

    EloquentRouter::prefix('inventory')
        ->handle(
            \App\Models\Inventories::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name'),
                    AllowedFilter::exact('status'),
                ]
            ]
        )->routes(function () {
            Route::post('/', \Modules\Admin\Actions\Inventory\PostAction::class . '@handle');
            Route::get('/{id}', \Modules\Admin\Actions\Inventory\GetDetailAction::class . '@handle');
            Route::post('/activeList', \Modules\Admin\Actions\Inventory\PostActiveListAction::class . '@handle');
        });

    EloquentRouter::prefix('order')
        ->handle(
            \App\Models\Orders::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,phone,email'), AllowedFilter::custom('phone', new \App\Builder\Filters\SearchLikeMultipleField, 'phone')
                ]
            ]
        )->routes(function () {
            Route::post('/', \Modules\Admin\Actions\Order\PostAction::class . '@handle');
            Route::get('/{id}', \Modules\Admin\Actions\Order\GetDetailAction::class . '@handle');
            Route::post('/assign', \Modules\Admin\Actions\Order\PostAssignAction::class . '@handle');
            Route::delete('/{id}', \Modules\Admin\Actions\Order\DeleteAction::class . '@handle');
            Route::post('/checkStock', \Modules\Admin\Actions\Order\PostCheckStockAction::class . '@handle');
//            Route::get('/{id}', \Modules\Admin\Actions\Patient\GetDetailAction::class . '@handle');
        });
});
