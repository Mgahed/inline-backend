<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ServiceController;
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
        Route::get('/{id}', [ServiceProviderController::class, 'details'])->name('service.provider.details');
    });

    ////////// branches
    Route::group(['prefix' => 'branch'], function () {
        Route::post('/add', [BranchController::class, 'add'])->name('add.branch');
        Route::post('/assign', [BranchController::class, 'assign_services'])->name('assign.service');
        Route::get('/{id}', [BranchController::class, 'branch_services'])->name('branch.services');
    });

    ////////// services
    Route::group(['prefix' => 'service'], function () {
        Route::get('/all', [ServiceController::class, 'all'])->name('all.service');
        Route::post('/add', [ServiceController::class, 'add'])->name('add.service');
    });

    ////////// simulate
    Route::group(['prefix' => 'simulate'], function () {
        Route::get('/increment/{id}', [BranchController::class, 'simulate_increment'])->name('simulate.increment.turn');
        Route::get('/reset/{id}', [BranchController::class, 'simulate_reset'])->name('simulate.reset');
        Route::get('/{branch_id}/{service_id}', [BranchController::class, 'simulate'])->name('simulate');
    });

});
