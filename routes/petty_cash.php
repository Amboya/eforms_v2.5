<?php

use Illuminate\Support\Facades\Route;


//petty cah routes
Route::get('home', [App\Http\Controllers\EForms\PettyCash\HomeController::class, 'index'])->name('petty.cash.home');
Route::get('list/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'index'])->name('petty.cash.list');
Route::get('create', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'create'])->name('petty.cash.create');
Route::post('show/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'show'])->name('petty.cash.show');
Route::post('store', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'store'])->name('petty.cash.store');
Route::post('approve', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'approve'])->name('petty.cash.approve');
Route::post('update', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'update'])->name('petty.cash.update');
Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'destroy'])->name('petty.cash.destroy');
Route::get('reports/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reports'])->name('petty.cash.report');
Route::get('report/export', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsExport'])->name('petty.cash.report.export');
Route::get('reports/sync', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsSync'])->name('petty.cash.report.sync');
Route::post('report/export/unmark/exported/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsExportUnmarkExported'])->name('petty.cash.report.export.unmark.exported');
Route::get('report/export/unmark/exported/all', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reportsExportUnmarkExportedAll'])->name('petty.cash.report.export.unmark.exported.all');
Route::get('sync/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'sync'])->name('petty.cash.sync');
Route::get('records/{value}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'records'])->name('petty.cash.record');
Route::post('void/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'void'])->name('petty.cash.void');
Route::post('reverse/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'reverse'])->name('petty.cash.reverse');
Route::post('search', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'search'])->name('petty.cash.search');

Route::get('charts', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'charts'])->name('petty.cash.charts');
Route::get('remove/duplicate/account/lines/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'markAccountLinesAsDuplicates'])->name('petty.cash.accounts.duplicate.remove');
Route::get('show/form/{id}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'showForm'])->name('petty.cash.reports.show');

Route::get('sync', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'syncAllPettyCash'])->name('petty.cash.sync.all');
Route::post('approve/batch/{status}', [App\Http\Controllers\EForms\PettyCash\PettyCashController::class, 'approveBatch'])->name('petty.cash.approve.batch');

//REPORTS
Route::group([
    'prefix' => 'report'
], function () {
    Route::get('directorates', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'index'])->name('petty.cash.reports.index');
    Route::get('sync/directorates', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'syncDirectorates'])->name('petty.cash.reports.sync.directorates');
    Route::get('sync/user/units', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'syncUserUnits'])->name('petty.cash.reports.sync.units');
});

Route::group([
    'prefix' => 'filtered/report'
], function () {
    Route::get('index', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'filteredReports'])->name('petty.cash.filtered.report');
    Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'getFilteredReports'])->name('petty.cash.filtered.get');
});

//        Route::group([
//            'prefix' => 'management'], function () {
//            Route::get('list', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'index'])->name('petty.cash.float.index');
//            Route::post('store', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'store'])->name('petty.cash.float.store');
//            Route::post('update', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'update'])->name('petty.cash.float.update');
//            Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'destroy'])->name('petty.cash.float.delete');
//        });
Route::group([
    'prefix' => 'float'], function () {
    Route::get('list', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'index'])->name('petty.cash.float.index');
    Route::post('store', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'store'])->name('petty.cash.float.store');
    Route::post('update', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'update'])->name('petty.cash.float.update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'destroy'])->name('petty.cash.float.delete');
});
Route::group([
    'prefix' => 'float/reimbursement'], function () {
    Route::get('list', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'index'])->name('petty.cash.float.reimbursement.index');
    Route::post('store', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'reimbursementStore'])->name('petty.cash.float.reimbursement.store');
    Route::get('show/{id}', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'reimbursementShow'])->name('petty.cash.float.reimbursement.show');
    Route::post('update', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'update'])->name('petty.cash.float.reimbursement.update');
    Route::post('destroy/{id}', [App\Http\Controllers\EForms\PettyCash\FloatController::class, 'destroy'])->name('petty.cash.float.reimbursement.delete');
});

//REPORTS
Route::group([
    'prefix' => 'finance'
], function () {
    Route::get('ready', [App\Http\Controllers\EForms\PettyCash\Integration::class, 'ready'])->name('petty.cash.finance.ready');
    Route::get('index', [App\Http\Controllers\EForms\PettyCash\Integration::class, 'index'])->name('petty.cash.finance.index');
    Route::post('send', [App\Http\Controllers\EForms\PettyCash\Integration::class, 'send'])->name('petty.cash.finance.send');
    Route::post('send-single/{form}', [App\Http\Controllers\EForms\PettyCash\Integration::class, 'sendSingle'])->name('petty.cash.finance.send.single');
    Route::get('header', [App\Http\Controllers\EForms\PettyCash\Integration::class, 'header'])->name('petty.cash.finance.header');
    Route::get('details/{item}', [App\Http\Controllers\EForms\PettyCash\Integration::class, 'details'])->name('petty.cash.finance.details');
//            Route::get('sync/user/units', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'syncUserUnits'])->name('petty.cash.reports.sync.units');
});




//config_work_flow for petty cash
Route::group([
    'prefix' => 'petty-cash/invoices'], function () {
    Route::get('units/{status}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'units'])->name('petty.cash.invoices.units');
    Route::post('units', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'unitsSearch'])->name('petty.cash.invoices.units.search');
    Route::get('directorates/{status}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'directorates'])->name('petty.cash.invoices.directorates');
    Route::post('directorates', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'directoratesSearch'])->name('petty.cash.invoices.directorates.search');
    Route::get('divisions/{status}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'divisions'])->name('petty.cash.invoices.divisions');
    Route::post('divisions', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'divisionsSearch'])->name('petty.cash.invoices.divisions.search');
    Route::get('duplicates/{status}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'duplicates'])->name('petty.cash.invoices.duplicates');
    Route::post('duplicates', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'duplicatesSearch'])->name('petty.cash.invoices.duplicates.search');
    Route::get('business/units/{status}', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'businessUnits'])->name('petty.cash.invoices.business.units');
    Route::post('business/units', [App\Http\Controllers\EForms\PettyCash\ReportsController::class, 'businessUnitsSearch'])->name('petty.cash.invoices.business.units.search');
});


Route::get('filtered/reports', \App\Http\Livewire\Eforms\PettyCash\Reports\FilteredReports::class)->name('petty.cash.filtered.reports');

