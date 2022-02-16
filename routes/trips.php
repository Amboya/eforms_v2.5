<?php

use Illuminate\Support\Facades\Route;

Route::get('home', [App\Http\Controllers\EForms\Trip\HomeController::class, 'index'])->name('trip.home');
Route::get('list/{value}', [App\Http\Controllers\EForms\Trip\TripController::class, 'index'])->name('trip.list');
Route::get('create', [App\Http\Controllers\EForms\Trip\TripController::class, 'create'])->name('trip.create');
Route::post('show/{id}', [App\Http\Controllers\EForms\Trip\TripController::class, 'show'])->name('trip.show');
Route::post('store', [App\Http\Controllers\EForms\Trip\TripController::class, 'store'])->name('trip.store');
Route::post('invite/{form}', [App\Http\Controllers\EForms\Trip\TripController::class, 'invite'])->name('trip.invite');
Route::post('approve', [App\Http\Controllers\EForms\Trip\TripController::class, 'approve'])->name('trip.approve');
Route::post('approve/{trip}', [App\Http\Controllers\EForms\Trip\TripController::class, 'membershipApprove'])->name('trip.approve.membership');
Route::post('update', [App\Http\Controllers\EForms\Trip\TripController::class, 'update'])->name('trip.update');
Route::post('destroy/{id}', [App\Http\Controllers\EForms\Trip\TripController::class, 'destroy'])->name('trip.destroy');
Route::get('reports', [App\Http\Controllers\EForms\Trip\TripController::class, 'reports'])->name('trip.report');
Route::get('reportExport', [App\Http\Controllers\EForms\Trip\TripController::class, 'reportsExport'])->name('trip.report-export');
Route::get('records/{value}', [App\Http\Controllers\EForms\Trip\TripController::class, 'records'])->name('trip.record');
Route::post('void/{id}', [App\Http\Controllers\EForms\Trip\TripController::class, 'void'])->name('trip.void');
Route::get('charts', [App\Http\Controllers\EForms\Trip\TripController::class, 'charts'])->name('trip.charts');



