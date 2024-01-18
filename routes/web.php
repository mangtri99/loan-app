<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\LoanController::class, 'index'])->name('dashboard');
    Route::get('/loan/{id}/detail', [App\Http\Controllers\LoanController::class, 'show'])->name('loan.show');
    Route::get('/loan/create', [App\Http\Controllers\LoanController::class, 'create'])->name('loan.create');
    // Route::get('/loan/{id}/edit', [App\Http\Controllers\LoanController::class, 'edit'])->name('loan.edit');
    Route::post('/loan', [App\Http\Controllers\LoanController::class, 'store'])->name('loan.store')->middleware([HandlePrecognitiveRequests::class]);
    Route::patch('/loan/{id}', [App\Http\Controllers\LoanController::class, 'update'])->name('loan.update');
    Route::post('/loan/{id}/approve', [App\Http\Controllers\LoanController::class, 'approve'])->name('loan.approve')->middleware([HandlePrecognitiveRequests::class]);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
