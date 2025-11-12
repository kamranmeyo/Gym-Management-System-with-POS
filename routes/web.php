<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeeReportController;
use App\Http\Controllers\PlanController;

use App\Http\Controllers\POS\ProductController as POSProductController;
use App\Http\Controllers\POS\SaleController as POSSaleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('members', MemberController::class);
    Route::post('/members/update/{id}', [MemberController::class, 'update'])->name('members.update');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/fee', [FeeController::class, 'index'])->name('fee.index');
    Route::post('/fee/search', [FeeController::class, 'search'])->name('fee.search');
    Route::post('/fee/update', [FeeController::class, 'update'])->name('fee.update');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/fee-report', [FeeReportController::class, 'index'])->name('fee.report');
});

Route::resource('plans', PlanController::class);



// //for memeber:
//     Route::middleware(['auth'])->group(function () {
//     Route::resource('members', MemberController::class);
// });

//------------ For POS
// Route::prefix('pos')->name('pos.')->middleware(['auth'])->group(function () {
//     Route::resource('products', ProductController::class);
//     Route::resource('sales', SaleController::class);
// });


Route::prefix('pos')->name('pos.')->middleware(['auth'])->group(function () {
    Route::resource('products', POSProductController::class);
    Route::resource('sales', POSSaleController::class);

        // Re-print route
    Route::get('sales/{sale}/print', [POSSaleController::class, 'print'])->name('sales.print');
        // Delete route
        
    // No need to define delete manually; resource already provides:
    //Route::delete('/{sale}', [POSSaleController::class, 'destroy'])->name('destroy');
});



require __DIR__.'/auth.php';
