<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/customers', fn () => redirect()->route('accounts.index'))->name('customers');
    Route::get('/support', fn () => redirect()->route('tickets.index'))->name('support');

    Route::resource('accounts', AccountController::class)->except('destroy');
    Route::resource('contacts', ContactController::class)->except('destroy');
    Route::resource('leads', LeadController::class)->except('destroy');
    Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');

    Route::get('/pipeline', [DealController::class, 'pipeline'])->name('pipeline.index');
    Route::resource('deals', DealController::class)->except('destroy');
    Route::patch('/deals/{deal}/stage', [DealController::class, 'updateStage'])->name('deals.stage.update');

    Route::resource('tasks', TaskController::class)->except(['show', 'destroy']);

    Route::resource('tickets', TicketController::class)->except('destroy');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status.update');
    Route::patch('/tickets/{ticket}/assignment', [TicketController::class, 'updateAssignment'])->name('tickets.assignment.update');
    Route::patch('/tickets/{ticket}/sla', [TicketController::class, 'refreshSla'])->name('tickets.sla.refresh');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/team', [TeamMemberController::class, 'index'])->name('team.index');
    Route::get('/team/members/create', [TeamMemberController::class, 'create'])->name('team.members.create');
    Route::post('/team/members', [TeamMemberController::class, 'store'])->name('team.members.store');
    Route::get('/team/members/{member}/edit', [TeamMemberController::class, 'edit'])->name('team.members.edit');
    Route::match(['put', 'patch'], '/team/members/{member}', [TeamMemberController::class, 'update'])->name('team.members.update');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
