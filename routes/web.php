<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Superadmin\{
    SiteController,
    PermissionController,
    RoleController,
    ComponentController

};

use App\Http\Controllers\UserController;

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

    Route::resource( strtolower(trans_choice('site.sites', 2)), SiteController::class)->names([
        'index' => 'sites'
    ])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('roles', RoleController::class)->names([
        'index' => 'roles'
    ]);
    Route::resource('permissions', PermissionController::class)->names([
        'index' => 'permissions'
    ]);
    Route::resource('components', ComponentController::class)->names([
        'index' => 'components'
    ]);

});

Route::middleware(['auth'])->group( function () {
    Route::resource(strtolower(trans_choice('site.users', 2)), UserController::class)->names([
        'index' => 'users'
    ])->only(['index', 'store', 'update', 'destroy']);
});
