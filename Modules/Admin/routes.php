<?php

use App\Builder\EloquentRouter;
use App\Models\EmailTemplate;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Actions\GetUserInfoAction;
use Modules\Admin\Actions\User\DeleteUserAction;
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
        })
        ->handle(\App\Models\User::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,username')]
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
        ->handle(\App\Models\Post::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'title')]
            ]
        )->routes(function () {

        });
    EloquentRouter::prefix('patient')
        ->handle(\App\Models\Patient::class,
            [
                'allowedFilters' => [
                    AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'full_name,phone,email')
                    ,AllowedFilter::custom('phone', new \App\Builder\Filters\SearchLikeMultipleField, 'phone')
                ]
            ]
        )->routes(function () {

        });
    EloquentRouter::prefix('websites')
        ->handle(\App\Models\Website::class,
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
        ->handle(\App\Models\Theme::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name')]
            ]
        );
    EloquentRouter::prefix('data-source')
        ->handle(\App\Models\DataSource::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name,url')]
            ]
        )->routes(function () {
            Route::post('/postUrls', \Modules\Admin\Actions\DataSource\TestPostUrlAction::class . '@handle');
            Route::post('/postDetail', \Modules\Admin\Actions\DataSource\TestPostDetailAction::class . '@handle');

        });
    EloquentRouter::prefix('tag')
        ->handle(\App\Models\Tag::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name')],
                'groupBy' => 'name'
            ]
        )->routes(function () {

        });
    EloquentRouter::prefix('file')
        ->handle(\App\Models\File::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'file_name')]
            ]
        )->routes(function () {
            Route::post('/Upload', \Modules\Admin\Actions\File\PostUploadAction::class . '@handle');
            Route::post('/Info', \Modules\Admin\Actions\File\PostInfoAction::class . '@handle');
        });

    EloquentRouter::prefix('email-template')
        ->handle(\App\Models\EmailTemplate::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'email_title,email_content')]
            ]
        );

    EloquentRouter::prefix('product')
        ->handle(\App\Models\Product::class,
            [
                'allowedFilters' => [AllowedFilter::custom('search', new \App\Builder\Filters\SearchLikeMultipleField, 'name')],
                'allowedIncludes' => ['images']
            ]
        )->routes(function () {
            Route::get('list', \Modules\Admin\Actions\Product\GetProductListAction::class . '@handle');
            Route::get('{id}', \Modules\Admin\Actions\Product\GetDetailAction::class . '@handle');
            Route::post('', \Modules\Admin\Actions\Product\PostAction::class . '@handle');
            Route::post('uploadImage', \Modules\Admin\Actions\Product\PostUploadImageAction::class . '@handle');
            Route::post('activeList', \Modules\Admin\Actions\Product\PostActiveListAction::class . '@handle');
        });


//    Route::prefix('/product')->group(function () {
//        Route::get('list', \Modules\Admin\Actions\Product\GetProductListAction::class . '@handle');
//        Route::get('{id}', \Modules\Admin\Actions\Product\GetProductDetailAction::class . '@handle');
//
//        Route::post('uploadImage', \Modules\Admin\Actions\Product\PostUploadImageAction::class . '@handle');
//        Route::post('', \Modules\Admin\Actions\Product\PostProductAction::class . '@handle');
//
//        Route::post('activeList', \Modules\Admin\Actions\Product\PostActiveListAction::class . '@handle');
//
//        Route::delete('{id}', \Modules\Admin\Actions\Product\DeleteProductAction::class . '@handle');
//    });

//     Route::prefix('/video')->group(function () {

//         Route::get('list', \Modules\Admin\Actions\Video\GetListAction::class . '@handle');
//         Route::get('{id}', \Modules\Admin\Actions\Video\GetDetailAction::class . '@handle');
//         Route::post('uploadVideo', \Modules\Admin\Actions\Video\PostUploadVideoAction::class . '@handle');
//         Route::post('', \Modules\Admin\Actions\Video\PostAction::class . '@handle');
//         Route::post('activeList', \Modules\Admin\Actions\Video\PostActiveListAction::class . '@handle');
//         Route::delete('{id}', \Modules\Admin\Actions\Video\DeleteAction::class . '@handle');
//     });

//     Route::prefix('/series')->group(function () {
//         Route::get('list', \Modules\Admin\Actions\Series\GetSeriesListAction::class . '@handle');
//         Route::get('customer-groups', \Modules\Admin\Actions\Series\GetCustomerGroupsAction::class . '@handle');
//         Route::get('{id}', \Modules\Admin\Actions\Series\GetSeriesDetailAction::class . '@handle');

//         Route::post('', \Modules\Admin\Actions\Series\PostSeriesAction::class . '@handle');
// //        Route::post('activeList', \Modules\Admin\Actions\Video\PostActiveListAction::class . '@handle');
//         Route::delete('{id}', \Modules\Admin\Actions\Series\DeleteSeriesAction::class . '@handle');
//     });

    Route::prefix('/config')->group(function () {
        Route::get('/', \Modules\Admin\Actions\Config\GetListAction::class . '@handle');
        Route::post('/', \Modules\Admin\Actions\Config\PostAction::class . '@handle');
        Route::post('/testSmtp', \Modules\Admin\Actions\Config\PostTestSmtpAction::class . '@handle');
    });
    // Route::prefix('/customer')->group(function () {
    //     Route::get('list', \Modules\Admin\Actions\Customer\GetListAction::class . '@handle');
    //     Route::post('activeList', \Modules\Admin\Actions\Customer\PostActiveListAction::class . '@handle');
    // });

    // Route::prefix('/customer-group')->group(function () {
    //     Route::get('list', \Modules\Admin\Actions\CustomerGroup\GetListAction::class . '@handle');
    //     Route::post('', \Modules\Admin\Actions\CustomerGroup\PostAction::class . '@handle');
    //     Route::get('{id}', \Modules\Admin\Actions\CustomerGroup\GetDetailAction::class . '@handle');
    //     Route::post('activeList', \Modules\Admin\Actions\CustomerGroup\PostActiveListAction::class . '@handle');
    //     Route::delete('{id}', \Modules\Admin\Actions\CustomerGroup\DeleteAction::class . '@handle');
    // });

    Route::prefix('/countries')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Countries\GetListAction::class . '@handle');
        Route::get('all', \Modules\Admin\Actions\Countries\GetAllAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Countries\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Countries\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Countries\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\Countries\DeleteAction::class . '@handle');
    });

    Route::prefix('/provinces')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Provinces\GetListAction::class . '@handle');
        Route::get('all', \Modules\Admin\Actions\Provinces\GetAllAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Provinces\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Provinces\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Provinces\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\Provinces\DeleteAction::class . '@handle');
    });

    Route::prefix('/districts')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Districts\GetListAction::class . '@handle');
        Route::get('all', \Modules\Admin\Actions\Districts\GetAllAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Districts\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Districts\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Districts\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\Districts\DeleteAction::class . '@handle');
    });


    Route::prefix('/wards')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Wards\GetListAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Wards\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Wards\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Wards\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\Wards\DeleteAction::class . '@handle');
    });

    Route::prefix('/shipping-method')->group(function () {
        Route::get('list', \Modules\Admin\Actions\ShippingMethod\GetListAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\ShippingMethod\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\ShippingMethod\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\ShippingMethod\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\ShippingMethod\DeleteAction::class . '@handle');
    });

    Route::prefix('/payment-method')->group(function () {
        Route::get('list', \Modules\Admin\Actions\PaymentMethod\GetListAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\PaymentMethod\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\PaymentMethod\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\PaymentMethod\PostActiveListAction::class . '@handle');
        Route::delete('{id}', \Modules\Admin\Actions\PaymentMethod\DeleteAction::class . '@handle');
    });

    Route::prefix('/orders')->group(function () {
        Route::get('list', \Modules\Admin\Actions\Orders\GetListAction::class . '@handle');
        Route::post('', \Modules\Admin\Actions\Orders\PostAction::class . '@handle');
        Route::get('{id}', \Modules\Admin\Actions\Orders\GetDetailAction::class . '@handle');
        Route::post('activeList', \Modules\Admin\Actions\Orders\PostActiveListAction::class . '@handle');
    });


});
