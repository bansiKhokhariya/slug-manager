<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\LoginController;
use App\Http\Controllers\backend\AuditTrailsController;
use App\Http\Controllers\backend\AdminusersController;
Route::get('admin-logout', [LoginController::class, 'adminLogout'])->name('admin-logout');

$adminPrefix = "";
Route::group(['prefix' => $adminPrefix, 'middleware' => ['admin']], function() {
    Route::get('my-dashboard', [DashboardController::class, 'myDashboard'])->name('my-dashboard');

    Route::get('edit-profile', [DashboardController::class, 'editProfile'])->name('edit-profile');
    Route::post('save-profile', [DashboardController::class, 'saveProfile'])->name('save-profile');

    Route::get('change-password', [DashboardController::class, 'change_password'])->name('change-password');
    Route::post('save-password', [DashboardController::class, 'save_password'])->name('save-password');

    $adminPrefix = "audittrails";
    Route::group(['prefix' => $adminPrefix, 'middleware' => ['admin']], function() {
        Route::get('audit-trails', [AuditTrailsController::class, 'list'])->name('audit-trails');
        Route::post('audit-trails-ajaxcall', [AuditTrailsController::class, 'ajaxcall'])->name('audit-trails-ajaxcall');
    });


    // Companies routes
    Route::get('admin-users/list', [AdminusersController::class, 'list'])->name('admin-users.list');
    Route::get('admin-users/add', [AdminusersController::class, 'add'])->name('admin-users.add');
    Route::post('admin-users/save-add', [AdminusersController::class, 'saveAdd'])->name('admin-users.save-add');
    Route::get('admin-users/edit/{editId}', [AdminusersController::class, 'edit'])->name('admin-users.edit');
    Route::post('admin-users/save-edit', [AdminusersController::class, 'saveEdit'])->name('admin-users.save-edit');
    Route::post('admin-users/ajaxcall', [AdminusersController::class, 'ajaxcall'])->name('admin-users.ajaxcall');
});
