<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\BlogWebController;
use App\Http\Controllers\Web\BrandWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\FormWebController;
use App\Http\Controllers\Web\PaymentWebController;
use App\Http\Controllers\Web\PricingWebController;
use App\Http\Controllers\Web\SubdomainWebController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('coming.soon')->group(function () {
    Route::domain('{brand}.' . env('APP_URL'))->group(function () {
        Route::get('/forms/{form_id}', [SubdomainWebController::class, 'showForm']);
    });

    Route::get('/blog', [BlogWebController::class, 'index']);
    Route::get('/blog/show', [BlogWebController::class, 'show']);
});

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('coming.soon');

Route::middleware(['auth', 'verified', 'coming.soon'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/forms', [FormWebController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormWebController::class, 'create'])->name('forms.create');
    Route::get('/forms/{form_id}', [FormWebController::class, 'edit'])->name('forms.edit');

    Route::get('/brand', [BrandWebController::class, 'index'])->name('brand.index');

    Route::get('/pricing', [PricingWebController::class, 'index'])->name('pricing.index');

    Route::get('/payments/{plan_name}', [PaymentWebController::class, 'index'])->name('payments.index');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with(__('general.verification_text'));
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/user/delete/{id}', [UserController::class, 'delete',
])->middleware('signed')->name('account.delete');

require __DIR__ . '/auth.php';
