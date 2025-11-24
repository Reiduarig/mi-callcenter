<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Agents\AgentIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('agents', AgentIndex::class)->name('agents.index');

     // Dashboard
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    // Staff
    Route::get('staff/absences', \App\Livewire\Staff\AbsenceIndex::class)->name('staff.absences.index');
    Route::get('staff/absences/create', \App\Livewire\Staff\AbsenceForm::class)->name('staff.absences.create');
    Route::get('staff/employees', \App\Livewire\Staff\EmployeeIndex::class)->name('staff.employees.index');
    Route::get('staff/employees/create', \App\Livewire\Staff\EmployeeForm::class)->name('staff.employees.create');
    Route::get('staff/shifts', \App\Livewire\Staff\ShiftIndex::class)->name('staff.shifts.index');
    Route::get('staff/shifts/create', \App\Livewire\Staff\ShiftForm::class)->name('staff.shifts.create');

    Route::post('logout', function () {
        auth()->logout();
        return redirect()->route('welcome');
    })->name('logout');
});

require __DIR__.'/auth.php';
