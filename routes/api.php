<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\MyController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\StripeController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('social', [AuthController::class, 'social']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user-profile', [AuthController::class, 'userProfile'])->middleware('auth:api');
});
Route::group(['middleware' => 'api'], function () {
    ///// provider
    Route::group(['middleware' => 'auth:api', 'prefix' => 'provider'], function () {
        Route::get('all', [ServiceProviderController::class, 'all_api']);
        Route::get('details', [ServiceProviderController::class, 'details_api']);
    });

    ///// branches
    Route::group(['middleware' => 'auth:api', 'prefix' => 'branch'], function () {
        Route::get('details', [BranchController::class, 'details_api']);
        Route::get('current-turn', [BranchController::class, 'current_turn']);
    });

    Route::group(['middleware' => 'auth:api', 'prefix' => 'reservation'], function () {
        Route::post('reserve',[ReservationController::class, 'reserve']);
        Route::get('mine',[ReservationController::class, 'my_reservation']);
    });

    Route::group(['prefix' => 'stripe'], function () {
        Route::get('pay', function () {
            return view('stripe.index');
        });
        Route::post('final-pay', [StripeController::class, 'stripe_order'])->name('stripe.order')->middleware('auth:api');
    });
});
//test github
Route::get('google-map', [MyController::class, 'google_map'])->middleware('api');
Route::get('sum', [MyController::class, 'my_controller'])->middleware('api');
