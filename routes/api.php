<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::post('login', 'Api\Auth\AuthController@login');
// Route::post('register', 'Api\Auth\AuthController@register');

// Route::group(['middleware' => ['auth:api', 'auth.watting']], function () {

//     /* User */
//     Route::get('user/details', 'Api\User\UserController@details')->name('user.details');

//     /* Browser */
//     Route::get('browser/recycle', 'Api\Browser\BrowserController@recycle')->name('browser.recycle');
//     Route::post('browser/restore', 'Api\Browser\BrowserController@restore')->name('browser.restore');
//     Route::middleware(['checkrole.browser'])->group(function () {
//         Route::get('browser/duplicate/{uuid}', 'Api\Browser\BrowserController@duplicate')->name('browser.duplicate');
//     });
//     Route::resource('browser', 'Api\Browser\BrowserController', [
//         'only' => ['show', 'store', 'destroy'],
//     ]);
//     Route::post('browser/update/{uuid}', 'Api\Browser\BrowserController@update')->name('browser.update');
//     Route::post('browser/close', 'Api\Browser\BrowserController@closeBrowser')->name('browser.close');
//     Route::get('browser/file/{url}', 'Api\Browser\BrowserController@getLinkDowndload')->name('browser.dowload');
//     Route::post('browser/ping', 'Api\Browser\BrowserController@CheckActivity')->name('browser.ping');

//     /* Share Browser*/
//     Route::resource('share', 'Api\Share\BrowserShareController', [
//         'only' => ['index', 'store', 'update', 'destroy'],
//     ]);
//     Route::get('share-retrieves/{uuid}', 'Api\Share\BrowserShareController@getSharedUserByBrowser')->name('share.retrieves');

//     /*  For shared users */
//     Route::middleware(['sharing'])->group(function () {
//         Route::get('share-browser/{uuid}', 'Api\Share\BrowserShareController@showBrowser')->name('share.showBrowser');
//         Route::post('share-browser/{uuid}', 'Api\Share\BrowserShareController@updateBrowser')->name('share.updateBrowser');
//         Route::post('share-browser/close/{uuid}', 'Api\Share\BrowserShareController@closeBrowser')->name('share.closeBrowser');
//         Route::delete('share-browser/unshare/{uuid}', 'Api\Share\BrowserShareController@refuse')->name('share.refuse');
//     });

//     /* Folder */
//     Route::resource('folder', 'Api\Folder\FolderController', [
//         'only' => ['index', 'store', 'update', 'destroy'],
//     ]);
//     Route::get('folder/{uuid}/retrieves-browser', 'Api\Folder\FolderController@getBrowserByFolder')->name('folder.getbrowser');
//     Route::post('folder/{uuid}/add-browser', 'Api\Folder\FolderController@addBrowserToFolder')->name('folder.addbrowser');
//     Route::post('folder/{uuid}/remove-browser', 'Api\Folder\FolderController@removeBrowserFromFolder')->name('folder.removebrowser');

//     /* Share Folder*/
//     Route::resource('sharefolder', 'Api\Share\FolderShareController', [
//         'only' => ['index', 'store', 'update', 'destroy'],
//     ]);
//     Route::get('sharefolder/shared/{uuid}', 'Api\Share\FolderShareController@retrieveSharedEmail')->name('sharefolder.shared');

//     /* Version */
//     Route::resource('version', 'Api\Version\VersionController', [
//         'only' => ['index', 'store'],
//     ]);
//     Route::get('version/dowload/{path}', 'Api\Version\VersionController@dowload')->name('version.dowload');

// });
