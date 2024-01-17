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
    Route::patch('/loan', [App\Http\Controllers\LoanController::class, 'update'])->name('loan.update')->middleware([HandlePrecognitiveRequests::class]);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/simulate', function(){
    $loan = 9674;
    $term = 3;
    $repayments = [];
    // create repayment for each loan
    for($i = 1; $i <= $term; $i++) {
        // loan amount / term
        // e.g. 1000 / 3 = 333.33
        $amount = $loan / $term;
        // dd($amount);
        // fix to 2 decimal places
        $amount = number_format((float) $amount, 2, '.', '');

        // if last term, add the remainder
        // e.g. 333.33 + 333.33 + 333.34 = 1000
        if($i == $term) {
            $amount = $amount + ($loan - ($amount * $term));
        }

        $repayments[] = [
            'amount' => (float) $amount,
        ];
    }
    dd($repayments);
});

require __DIR__.'/auth.php';
