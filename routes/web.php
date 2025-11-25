<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Actions\Logout;
use App\Livewire\Staff\UserIndex;
use App\Livewire\Staff\UserForm;
use App\Livewire\Staff\ShiftIndex;
use App\Livewire\Staff\ShiftForm;
use App\Livewire\Staff\ShiftCalendar;
use App\Livewire\Staff\ShiftTemplateIndex;
use App\Livewire\Staff\ShiftTemplateForm;
use App\Livewire\Staff\AbsenceIndex;
use App\Livewire\Staff\AbsenceForm;
use App\Livewire\Staff\RoleIndex;
use App\Livewire\Staff\RoleForm;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    // Dashboard - Todos los usuarios autenticados
    Route::get('/dashboard', \App\Livewire\Dashboard::class)
        ->middleware('permission:view-dashboard')
        ->name('dashboard');

    // Usuarios - Solo gerente puede gestionar completamente
    Route::middleware('permission:view-users')->group(function () {
        Route::get('staff/users', UserIndex::class)->name('staff.users.index');
    });

    Route::middleware('permission:create-users')->group(function () {
        Route::get('staff/users/create', UserForm::class)->name('staff.users.create');
    });

    Route::middleware('permission:edit-users')->group(function () {
        Route::get('staff/users/{userId}/edit', UserForm::class)->name('staff.users.edit');
    });

    // Turnos - Permisos según rol
    Route::middleware('permission:view-shifts')->group(function () {
        Route::get('staff/shifts', ShiftIndex::class)->name('staff.shifts.index');
        Route::get('staff/shifts/calendar', ShiftCalendar::class)->name('staff.shifts.calendar');
    });

    Route::middleware('permission:create-shifts')->group(function () {
        Route::get('staff/shifts/create', ShiftForm::class)->name('staff.shifts.create');
    });

    Route::middleware('permission:edit-shifts')->group(function () {
        Route::get('staff/shifts/{shiftId}/edit', ShiftForm::class)->name('staff.shifts.edit');
    });

    // Plantillas de Turnos - Solo gerente y coordinador
    Route::middleware('permission:manage-shift-templates')->group(function () {
        Route::get('staff/shift-templates', ShiftTemplateIndex::class)->name('staff.shift-templates.index');
        Route::get('staff/shift-templates/create', ShiftTemplateForm::class)->name('staff.shift-templates.create');
        Route::get('staff/shift-templates/{id}/edit', ShiftTemplateForm::class)->name('staff.shift-templates.edit');
    });

    // Ausencias - Agentes pueden crear sus propias ausencias
    Route::middleware('permission:view-absences')->group(function () {
        Route::get('staff/absences', AbsenceIndex::class)->name('staff.absences.index');
    });

    Route::middleware('permission:create-absences')->group(function () {
        Route::get('staff/absences/create', AbsenceForm::class)->name('staff.absences.create');
    });

    Route::middleware('permission:edit-absences')->group(function () {
        Route::get('staff/absences/{absenceId}/edit', AbsenceForm::class)->name('staff.absences.edit');
    });

    // Roles y Permisos - Solo administrador
    Route::middleware('permission:manage-roles')->group(function () {
        Route::get('staff/roles', RoleIndex::class)->name('staff.roles.index');
        Route::get('staff/roles/create', RoleForm::class)->name('staff.roles.create');
        Route::get('staff/roles/{roleId}/edit', RoleForm::class)->name('staff.roles.edit');
    });

    Route::post('logout', function () {
        (new Logout())();
        return redirect('/');
    })->name('logout');
});

require __DIR__.'/auth.php';
