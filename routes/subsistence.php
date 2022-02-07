<?php

use Illuminate\Support\Facades\Route;

Route::get('home', [App\Http\Controllers\EForms\Subsistence\HomeController::class, 'index'])->name('home');
Route::get('list/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'index'])->name('list');
Route::post('create/{trip}/{invitation}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'create'])->name('subscribe');
Route::post('show/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'show'])->name('show');
Route::get('show/now/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'show'])->name('show.id');
Route::post('store/{trip}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'store'])->name('store');
Route::post('approve', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'approve'])->name('approve');
Route::post('update', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'update'])->name('update');
Route::post('destroy/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'destroy'])->name('destroy');
Route::get('reports/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reports'])->name('report');
Route::get('reportExport', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExport'])->name('report.export');
Route::get('reportSync', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsSync'])->name('report.sync');
Route::post('reportExportUnmarkExported/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExportUnmarkExported'])->name('report.unmark.exported');
Route::get('reportExportUnmarkExportedAll', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reportsExportUnmarkExportedAll'])->name('report.export.unmark.exported.all');
Route::get('sync/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'sync'])->name('sync');
Route::get('records/{value}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'records'])->name('record');
Route::post('void/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'void'])->name('void');
Route::post('reverse/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'reverse'])->name('reverse');
Route::post('search', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'search'])->name('search');

Route::get('charts', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'charts'])->name('charts');
Route::get('removeDuplicateAccountLines/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'markAccountLinesAsDuplicates'])->name('accounts.duplicate-remove');
Route::get('showForm/{id}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'showForm'])->name('reports.show');
Route::post('approve/batch/{status}', [App\Http\Controllers\EForms\Subsistence\SubsistenceController::class, 'approveBatch'])->name('approve.batch');

//REPORTS
Route::group([
    'prefix' => 'report'
], function () {
    Route::get('directorates', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'index'])->name('reports.index');
    Route::get('syncDirectorates', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'syncDirectorates'])->name('reports.sync.directorates');
    Route::get('syncUserUnits', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'syncUserUnits'])->name('reports.sync.units');

});



Route::group([
    'prefix' => 'filtered/report'
], function () {
    Route::get('index', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'filteredReports'])->name('filtered.report');
    Route::get('get/{unit}/{status}/{start_date}/{end_date}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'getFilteredReports'])->name('filtered.get');
});



//REPORTS
Route::group([
    'prefix' => 'finance'
], function () {
    Route::get('ready', [App\Http\Controllers\EForms\Subsistence\Integration::class, 'ready'])->name('finance.ready');
    Route::get('index', [App\Http\Controllers\EForms\Subsistence\Integration::class, 'index'])->name('finance.index');
    Route::post('send', [App\Http\Controllers\EForms\Subsistence\Integration::class, 'send'])->name('finance.send');
    Route::get('header', [App\Http\Controllers\EForms\Subsistence\Integration::class, 'header'])->name('finance.header');
});



//config_work_flow for petty cash
Route::group([
    'prefix' => 'invoices'], function () {
    Route::get('units/{status}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'units'])->name('invoices.units');
    Route::post('units', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'unitsSearch'])->name('invoices.units.search');
    Route::get('directorates/{status}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'directorates'])->name('invoices.directorates');
    Route::post('directorates', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'directoratesSearch'])->name('invoices.directorates.search');
    Route::get('divisions/{status}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'divisions'])->name('invoices.divisions');
    Route::post('divisions', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'divisionsSearch'])->name('invoices.divisions.search');
    Route::get('duplicates/{status}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'duplicates'])->name('invoices.duplicates');
    Route::post('duplicates', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'duplicatesSearch'])->name('invoices.duplicates.search');
    Route::get('business/units/{status}', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'businessUnits'])->name('invoices.business.units');
    Route::post('business/units', [App\Http\Controllers\EForms\Subsistence\ReportsController::class, 'businessUnitsSearch'])->name('invoices.business.units.search');
});



