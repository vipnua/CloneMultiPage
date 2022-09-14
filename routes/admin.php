<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register manager routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! hehe
|
| Format name route: name.function, example: dashboard.index
 */

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes(['register' => false, 'verify' => false, 'reset' => false]);
Route::middleware(['auth:admin'])->group(function () {
    /* Authenticate */
    Route::get('logout', 'Auth\\LoginController@logout');

    /* Dasboard manager */
    Route::get('/', 'Backend\\DashboardController@index')->name('dashboard');
    Route::post('dashboard/report-payment/date', 'Backend\\Dashboard\ReportPaymentController@getReportByDate')->name('dashboard.report_payment.date');
    Route::resource('dashboard/report-payment', 'Backend\\Dashboard\ReportPaymentController');
    Route::post('dashboard/date', 'Backend\\DashboardController@getReportByDate')->name('dashboard.date');
    Route::resource('dashboard', 'Backend\\DashboardController');

    /* Group: employee, customer */
    Route::prefix('manager')->group(function () {
        /* Employee manager */
        Route::resource('admin', 'Backend\\AdminController');
        Route::get('profile', 'Backend\\AdminController@profile')->name('profile.view');
        Route::post('profile', 'Backend\\AdminController@postProfile')->name('profile.store');
        Route::post('change-password', 'Backend\\AdminController@changePassword')->name('password.update');

        /* Customer manager */
        Route::resource('user', 'Backend\Manager\UserController');
        Route::get('user-profile/{uuid}', 'Backend\Manager\UserController@userProfile')->name('user.profile');
        Route::get('user-table-profile/{uuid}', 'Backend\Manager\UserController@getProfileTable')->name('user.tableprofile');
        Route::put('user-status', 'Backend\Manager\UserController@userStatus')->name('user.status');
        Route::put('user-change-status', 'Backend\Manager\UserController@userChangeStatus')->name('user.changestatus');
        Route::put('profile-config', 'Backend\Manager\UserController@configProfile')->name('user.config');

        /* Plan charge */
        Route::resource('plan', 'Backend\Manager\PlanController');

        /* Plan manager */
        Route::get('plan-manager/all/', 'Backend\Manager\PlanManagerController@getAllPlan')->name('plan-manager.all');
        Route::post('plan-manager/default/', 'Backend\Manager\PlanManagerController@setDefault')->name('plan-manager.setDefault');
        Route::resource('plan-manager', 'Backend\Manager\PlanManagerController');

        /* Payment method */
        Route::post('payment-method/update/{id}', 'Backend\Manager\PaymentMethodController@update')->name('payment-method.update');
        Route::resource('payment-method', 'Backend\Manager\PaymentMethodController');

        /* Payment method */
//        Route::post('payment-method/update/{id}', 'Backend\Manager\PaymentMethodController@update')->name('payment-method.update');
        Route::resource('payment-history', 'Backend\Manager\PaymentHistoryController');

        /* Discount */
        Route::resource('discount', 'Backend\Manager\DiscountController');
        /* Feedback */
        Route::resource('feedback', 'Backend\Manager\FeedbackController');
        Route::patch('feedback/{id}', 'FeedbackController@update')->name('feedback.update');
    });

    /* Group: role, permission, function, userassign */
    Route::prefix('system')->group(function () {

        /* Role manager */
        Route::resource('role', 'Backend\System\RoleController');

        /* Function manager */
        Route::resource('function', 'Backend\System\FunctionController');

        /* Permission manager */
        Route::resource('permission', 'Backend\System\PermissionController');

        /* Userassign manager */
        Route::resource('userassign', 'Backend\System\UserAssignController');
    });

    /* Group: resource */
    Route::prefix('browser')->group(function () {
        /* Resource */
        Route::resource('resource', 'Backend\Browser\ResourceController');
        Route::put('resource-status', 'Backend\Browser\ResourceController@resourceStatus')->name('resource.status');
        Route::get('resource/download/{path}', 'Backend\Browser\ResourceController@download')->name('resource.download');
    });

    /* Recycle bin */
    Route::resource('bin', 'Backend\Manager\BinController');

});
