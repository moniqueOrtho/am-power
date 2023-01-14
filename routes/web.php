<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Superadmin\{
    PermissionController,
    RoleController,
    ComponentController

};

use App\Http\Controllers\{
    PageController,
    SiteController,
    UserController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:superadmin'])->group( function () {


    Route::resource('roles', RoleController::class)->names([
        'index' => 'roles'
    ])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('permissions', PermissionController::class)->names([
        'index' => 'permissions'
    ])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('components', ComponentController::class)->names([
        'index' => 'components'
    ])->only(['index', 'store', 'update', 'destroy']);

});

Route::middleware(['auth'])->group( function () {
    Route::resource(strtolower(trans_choice('site.users', 2)), UserController::class)->names([
        'index' => 'users'
    ])->only(['index', 'store', 'update', 'destroy']);

    Route::resource( __('site.pages_slug'), PageController::class)->only(['store', 'update', 'destroy']);

    Route::resource( strtolower(trans_choice('site.sites', 2)), SiteController::class)->names([
        'index' => 'sites'
    ])->only(['index', 'store', 'update', 'destroy']);

    Route::get('/' . strtolower(trans_choice('site.subscribers', 2)) . '/{siteId}' , [UserController::class, 'indexSubscribers'] )->name('subscribers');

    Route::get('/' . __('site.pages_slug') . '/{siteId}' , [PageController::class, 'index'] )->name('pages');

    Route::get('/' . strtolower(trans_choice('site.pages', 1)) . '/{id}', [PageController::class, 'show']);
});
