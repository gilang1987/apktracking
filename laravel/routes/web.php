<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/* Components Routes */
Route::group(['middleware' => ['web']], function () {
    Route::get('photo/{folder}/{filename}', function ($folder, $filename) {
        $path = storage_path('app/public/files/'.$folder.'/');
        try {
            return response()->file($path.$filename);
            // return Storage::get('public/files/membership/'.$filename.'');
        } catch (Exception $error) {
            return abort('404');
        }
    });
});
/* Components Routes */

/* Ajax Routes */
Route::group(['prefix' => 'ajax'], function() {
});
/* Ajax Routes */


/* User Menu Routes */
Route::group(['namespace' => 'User', 'middleware' => ['auth', 'cookie', 'maintenance']], function() {
    Route::get('/', [\App\Http\Controllers\User\Auth\LoginController::class, 'index'])->withoutMiddleware(['auth']);
    Route::group(['prefix' => 'auth'], function() {
        Route::get('activate/{target:token}', [\App\Http\Controllers\User\Auth\ActivateAccountController::class, 'index'])->withoutMiddleware(['auth', 'cookie']);
        Route::get('login', [\App\Http\Controllers\User\Auth\LoginController::class, 'getLogin'])->withoutMiddleware(['auth'])->name('user.login');
        Route::post('login', [\App\Http\Controllers\User\Auth\LoginController::class, 'postLogin'])->withoutMiddleware(['auth'])->name('user.login');
        Route::get('logout', [\App\Http\Controllers\User\Auth\LoginController::class, 'logout'])->withoutMiddleware(['auth']);
        if (website_config('main')->is_register_enabled) {
            Route::get('register', [\App\Http\Controllers\User\Auth\RegisterController::class, 'getRegister'])->withoutMiddleware(['auth'])->name('user.register');
            Route::post('register', [\App\Http\Controllers\User\Auth\RegisterController::class, 'postRegister'])->withoutMiddleware(['auth'])->name('user.register');
        }
        if (website_config('main')->is_reset_password_enabled) {
            Route::get('reset/{token:hash?}', [\App\Http\Controllers\User\Auth\ResetPasswordController::class, 'getReset'])->withoutMiddleware(['auth'])->name('user.reset');
            Route::post('reset/{token:hash?}', [\App\Http\Controllers\User\Auth\ResetPasswordController::class, 'postReset'])->withoutMiddleware(['auth'])->name('user.reset');
        }
    });
    Route::group(['prefix' => 'account'], function() {
        Route::get('profile', [\App\Http\Controllers\User\AccountController::class, 'profile']);
        Route::get('login_logs', [\App\Http\Controllers\User\AccountController::class, 'login_logs']);
        Route::get('settings', [\App\Http\Controllers\User\AccountController::class, 'getSettings']);
        Route::patch('settings', [\App\Http\Controllers\User\AccountController::class, 'postSettings']);
    });
    Route::group(['prefix' => 'permission'], function() {
        Route::get('list', [\App\Http\Controllers\User\PermissionController::class, 'list']);
        Route::get('form', [\App\Http\Controllers\User\PermissionController::class, 'getForm']);
        Route::post('form', [\App\Http\Controllers\User\PermissionController::class, 'postForm']);
        Route::get('detail/{target:id}', [\App\Http\Controllers\User\PermissionController::class, 'detail']);
    });
    Route::group(['prefix' => 'inout'], function() {
        Route::get('list', [\App\Http\Controllers\User\InoutController::class, 'list']);
        Route::get('form', [\App\Http\Controllers\User\InoutController::class, 'getForm']);
        Route::post('form', [\App\Http\Controllers\User\InoutController::class, 'postForm']);
        Route::get('detail/{target:id}', [\App\Http\Controllers\User\InoutController::class, 'detail']);
    });
    Route::group(['prefix' => 'page'], function() {
        Route::get('site/{target:slug}', [\App\Http\Controllers\User\PageController::class, 'site'])->withoutMiddleware(['auth']);
    });
});
/* User Menu Routes */


/* Admin Menu Routes */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth:admin']], function() {
    Route::get('/', [\App\Http\Controllers\Admin\AuthController::class, 'index']);
    Route::group(['prefix' => 'auth'], function() {
        Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'getLogin'])->withoutMiddleware(['auth:admin', 'status'])->name('admin.login');
        Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'postLogin'])->withoutMiddleware(['auth:admin', 'status'])->name('admin.login');
        Route::get('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->withoutMiddleware(['auth:admin', 'status']);
    });
    Route::group(['prefix' => 'admin'], function() {
        Route::get('list', [\App\Http\Controllers\Admin\AdminController::class, 'list']);
        Route::get('form/{target:id?}', [\App\Http\Controllers\Admin\AdminController::class, 'getForm']);
        Route::post('form/{target:id?}', [\App\Http\Controllers\Admin\AdminController::class, 'postForm']);
        Route::get('delete/{target:id}', [\App\Http\Controllers\Admin\AdminController::class, 'delete']);
        Route::get('status/{target:id}/{status:status}', [\App\Http\Controllers\Admin\AdminController::class, 'status']);
    });
    Route::group(['prefix' => 'user'], function() {
        Route::get('list', [\App\Http\Controllers\Admin\UserController::class, 'list']);
        Route::get('form/{target:id?}', [\App\Http\Controllers\Admin\UserController::class, 'getForm']);
        Route::post('form/{target:id?}', [\App\Http\Controllers\Admin\UserController::class, 'postForm']);
        Route::get('detail/{target:id}', [\App\Http\Controllers\Admin\UserController::class, 'detail']);
        Route::get('delete/{target:id}', [\App\Http\Controllers\Admin\UserController::class, 'delete']);
        Route::get('status/{target:id}/{status:status}', [\App\Http\Controllers\Admin\UserController::class, 'status']);
    });
    Route::group(['prefix' => 'permission'], function() {
        Route::get('list', [\App\Http\Controllers\Admin\PermissionController::class, 'list']);
        Route::get('form', [\App\Http\Controllers\Admin\PermissionController::class, 'getForm']);
        Route::post('form', [\App\Http\Controllers\Admin\PermissionController::class, 'postForm']);
        Route::get('detail/{target:id}', [\App\Http\Controllers\Admin\PermissionController::class, 'detail']);
        Route::get('delete/{target:id}', [\App\Http\Controllers\Admin\PermissionController::class, 'delete']);
        Route::get('approve/{target:id}', [\App\Http\Controllers\Admin\PermissionController::class, 'approve']);
        Route::get('reject/{target:id}', [\App\Http\Controllers\Admin\PermissionController::class, 'reject']);
    });
    Route::group(['prefix' => 'log'], function() {
        Route::get('user_register', [\App\Http\Controllers\Admin\LogController::class, 'user_register']);
        Route::get('user_login', [\App\Http\Controllers\Admin\LogController::class, 'user_login']);
        Route::get('admin_login', [\App\Http\Controllers\Admin\LogController::class, 'admin_login']);
    });
    Route::group(['prefix' => 'settings'], function() {
        Route::group(['prefix' => 'website_page'], function() {
            Route::get('list', [\App\Http\Controllers\Admin\Settings\WebsitePageController::class, 'list']);
            Route::get('form/{target:id?}', [\App\Http\Controllers\Admin\Settings\WebsitePageController::class, 'getForm']);
            Route::post('form/{target:id?}', [\App\Http\Controllers\Admin\Settings\WebsitePageController::class, 'postForm']);
            Route::get('delete/{target:id}', [\App\Http\Controllers\Admin\Settings\WebsitePageController::class, 'delete']);
        });
        Route::group(['prefix' => 'website_configs'], function() {
            Route::get('/', [\App\Http\Controllers\Admin\Settings\WebsiteConfigController::class, 'getIndex']);
            Route::patch('/', [\App\Http\Controllers\Admin\Settings\WebsiteConfigController::class, 'postIndex']);
            Route::get('delete_logo', [\App\Http\Controllers\Admin\Settings\WebsiteConfigController::class, 'delete_logo']);
            Route::get('delete_favicon', [\App\Http\Controllers\Admin\Settings\WebsiteConfigController::class, 'delete_favicon']);
            Route::get('test_email', [\App\Http\Controllers\Admin\Settings\WebsiteConfigController::class, 'test_email']);
        });
    });
});
/* Admin Menu Routes */

