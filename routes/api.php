<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Sale\GetAllSale;
use App\Actions\Sale\AddSale;
use App\Actions\Sale\UpdateSale;
use App\Actions\Product\GetAllProduct;
use App\Actions\Sale\DeleteSale;

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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('/sales')->middleware('auth:sanctum')->name('api.sales.')->group(static function () {
    Route::get('/', GetAllSale::class)->name('index');
    Route::post('/', AddSale::class)->name('store');
    Route::patch('/{sale}', UpdateSale::class)->name('update');
    Route::delete('/{sale}', DeleteSale::class)->name('delete');
});

Route::prefix('/products')->middleware('auth:sanctum')->group(static function () {
    Route::get('/', GetAllProduct::class);
});
