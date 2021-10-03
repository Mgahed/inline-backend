<?php

use App\Http\Controllers\ServiceProviderController;
use Illuminate\Support\Facades\Route;

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

Auth::routes();
Route::group(['middleware' => 'admin'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    ////////// service provider
    Route::group(['prefix' => 'service-provider'], function () {
        Route::get('/all', [ServiceProviderController::class, 'all'])->name('all.service.provider');
        Route::post('/add', [ServiceProviderController::class, 'add'])->name('add.service.provider');
    });
});
