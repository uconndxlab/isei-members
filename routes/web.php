<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;


Route::get('/', [MemberController::class, 'index'])->name('members.index');

// Apply middleware to a route
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin', [MemberController::class, 'index'])->name('admin.index');
    // members edit
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('admin.members.edit');

    // members update
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('admin.members.update');

    // members destroy
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('admin.members.destroy');

    // members create
    Route::get('/members/create', [MemberController::class, 'create'])->name('admin.members.create');

    // members store
    Route::post('/members', [MemberController::class, 'store'])->name('admin.members.store');
    
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');