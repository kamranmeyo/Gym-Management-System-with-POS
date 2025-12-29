<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeeReportController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\HikvisionController;
use App\Http\Controllers\developer\HikvisionSettingController;
use App\Http\Controllers\Expense\ExpenseCategoryController;
use App\Http\Controllers\Expense\ExpenseController;
use App\Http\Controllers\POS\ProductController as POSProductController;
use App\Http\Controllers\POS\SaleController as POSSaleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('members', MemberController::class);
    Route::post('/members/update/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/sync-machine', [MemberController::class, 'syncMembersToMachine']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/fee', [FeeController::class, 'index'])->name('fee.index');
    Route::post('/fee/search', [FeeController::class, 'search'])->name('fee.search');
    Route::post('/fee/update', [FeeController::class, 'update'])->name('fee.update');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/fee-report', [FeeReportController::class, 'index'])->name('fee.report');
    Route::get('/fee/print/{member}', [FeeController::class, 'print'])->name('fee.print');
});

Route::resource('plans', PlanController::class);


//Expense

Route::resource('expenses', ExpenseController::class);
Route::get('expense-report', [ExpenseController::class,'report'])->name('expenses.report');

//Expense-Category
Route::get('/expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
Route::post('/expense-categories/store', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
Route::put('/expense-categories/{category}',[ExpenseCategoryController::class, 'update'])->name('expense-categories.update');

Route::delete('/expense-categories/{category}',[ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
Route::resource('expense-categories', ExpenseCategoryController::class);

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

Route::get('/hikvision/device-info', [HikvisionController::class, 'deviceInfo']);
Route::get('/hikvision/checkcapability', [HikvisionController::class, 'checkcapability']);
Route::get('/hikvision/UserInfoCount', [HikvisionController::class, 'UserInfoCount']);
Route::get('/hikvision/ListAllPersons', [HikvisionController::class, 'ListAllPersons']);
Route::get('/hikvision/systemIOpability', [HikvisionController::class, 'systemIOpability']);
Route::get('/hikvision/UserInfoCapabilities', [HikvisionController::class, 'UserInfoCapabilities']);
Route::get('/hikvision/UserInfoDeleteCapabilities', [HikvisionController::class, 'UserInfoDeleteCapabilities']);
Route::get('/hikvision/search-employee', [HikvisionController::class, 'searchPersonByEmployeeNo']);
Route::get('/hikvision/searchPersonByEmployeeNoAllDetails', [HikvisionController::class, 'searchPersonByEmployeeNoAllDetails']);
Route::get('/hikvision/ArmingwithoutSubscription', [HikvisionController::class, 'ArmingwithoutSubscription']);
Route::match(['get','put'], '/hikvision/Eventcapabilities', [HikvisionController::class, 'Eventcapabilities']);
Route::match(['get','put'], '/hikvision/EventSearch', [HikvisionController::class, 'EventSearch']);
Route::match(['get','put'], '/hikvision/getAttendanceEvents', [HikvisionController::class, 'getAttendanceEvents']);
Route::match(['get','put'], '/hikvision/edit-person', [HikvisionController::class, 'editPerson']);
Route::match(['get','post'], '/hikvision/add-person', [HikvisionController::class, 'addPerson']);




// /developer/hikvision-settings
Route::prefix('developer')->middleware(['auth'])->group(function () {

    Route::get('/hikvision-settings', [HikvisionSettingController::class, 'index'])
        ->name('developer.hikvision.settings');

    Route::post('/hikvision-settings', [HikvisionSettingController::class, 'save'])
        ->name('developer.hikvision.save');

});



require __DIR__.'/auth.php';
