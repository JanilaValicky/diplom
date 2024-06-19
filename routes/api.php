<?php

use App\Http\Controllers\Api\AvatarApiController;
use App\Http\Controllers\Api\BrandApiController;
use App\Http\Controllers\Api\FormApiController;
use App\Http\Controllers\Api\FormExportApiController;
use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('dashboard/forms')->group(function () {
    Route::get('/', [FormApiController::class, 'index'])->name('api.forms.index');
    Route::get('/{id}', [FormApiController::class, 'show'])->middleware('users.form')->name('api.forms.get');
    Route::post('/', [FormApiController::class, 'store'])->name('api.forms.create');
    Route::put('/{id}', [FormApiController::class, 'update'])->middleware('users.form')->name('api.forms.update');
    Route::delete('/{id}', [FormApiController::class, 'destroy'])->middleware('users.form')->name('api.forms.delete');
    Route::get('/export/excel', [FormExportApiController::class, 'exportExcel'])->name('api.forms.export.excel');
    Route::get('/export/csv', [FormExportApiController::class, 'exportCsv'])->name('api.forms.export.csv');
});

Route::prefix('dashboard/brand')->group(function () {
    Route::get('/', [BrandApiController::class, 'show'])->middleware('user.has.brand')->name('api.brand.index');
    Route::post('/', [BrandApiController::class, 'store'])->middleware('user.doesnt.have.brand')->name('api.brand.create');
    Route::get('/exists', [BrandApiController::class, 'brandNameExists'])->name('api.brand.exists');
});

Route::prefix('dashboard/profile')->group(function () {
    Route::post('/avatar', [AvatarApiController::class, 'upload'])->name('api.avatar.upload');
    Route::delete('/avatar', [AvatarApiController::class, 'delete'])->name('api.avatar.delete');
});

Route::prefix('dashboard/payment')->group(function () {
    Route::post('/{plan_name}', [PaymentApiController::class, 'checkout'])->name('api.payment.checkout');
    Route::get('/success', [PaymentApiController::class, 'success'])->name('api.payment.success');
});
