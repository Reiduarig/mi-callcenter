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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('agents', AgentIndex::class)->name('agents.index');
});

require __DIR__.'/auth.php';
