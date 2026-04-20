<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrmController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CrmController::class, 'dashboard'])->name('dashboard');
    Route::get('/leads', [CrmController::class, 'leads'])->name('leads');
    Route::get('/customers', [CrmController::class, 'customers'])->name('customers');
    Route::get('/pipeline', [CrmController::class, 'pipeline'])->name('pipeline');
    Route::get('/tasks', [CrmController::class, 'tasks'])->name('tasks');
    Route::get('/support', [CrmController::class, 'support'])->name('support');
    Route::get('/reports', [CrmController::class, 'reports'])->name('reports');
    Route::get('/team', [CrmController::class, 'team'])->name('team');
    Route::get('/billing', [CrmController::class, 'billing'])->name('billing');
    Route::get('/integrations', [CrmController::class, 'integrations'])->name('integrations');
    Route::get('/settings', [CrmController::class, 'settings'])->name('settings');
    Route::post('/team/members', [CrmController::class, 'storeMember'])->name('team.members.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
