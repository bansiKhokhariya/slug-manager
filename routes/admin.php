<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\LoginController;
use App\Http\Controllers\backend\AuditTrailsController;
use App\Http\Controllers\backend\AdminusersController;
use App\Http\Controllers\backend\SlugController;
use App\Http\Controllers\backend\DocumentController;

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

    // Slug routes
    Route::get('slug/list', [SlugController::class, 'list'])->name('slug.list');
    Route::get('slug/add', [SlugController::class, 'add'])->name('slug.add');
    Route::post('slug/save-add', [SlugController::class, 'saveAdd'])->name('slug.save-add');
    Route::get('slug/edit/{editId}', [SlugController::class, 'edit'])->name('slug.edit');
    Route::post('slug/save-edit', [SlugController::class, 'saveEdit'])->name('slug.save-edit');
    Route::post('slug/ajaxcall', [SlugController::class, 'ajaxcall'])->name('slug.ajaxcall');

    // Document routes
    Route::get('document/list', [DocumentController::class, 'list'])->name('document.list');
    Route::get('document/add', [DocumentController::class, 'add'])->name('document.add');
    Route::post('document/save-add', [DocumentController::class, 'saveAdd'])->name('document.save-add');
    Route::get('document/edit/{editId}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('document/save-edit', [DocumentController::class, 'saveEdit'])->name('document.save-edit');
    Route::post('document/ajaxcall', [DocumentController::class, 'ajaxcall'])->name('document.ajaxcall');

    Route::get('document/generate-pdf/{id}', [DocumentController::class, 'generatePdf'])->name('document.generate-pdf');


});
