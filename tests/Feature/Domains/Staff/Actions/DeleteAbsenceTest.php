<?php

use App\Domains\Staff\Actions\DeleteAbsence;
use App\Domains\Staff\Enums\AbsenceStatus;
use App\Domains\Staff\Enums\AbsenceType;
use App\Domains\Staff\Models\Absence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('deleting an approved vacation absence restores vacation days', function () {
    $user = User::factory()->create([
        'annual_vacation_days' => 20,
        'used_vacation_days' => 0,
    ]);

    // Crear una ausencia de vacaciones aprobada
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => '2025-12-01',
        'end_date' => '2025-12-05',
        'status' => AbsenceStatus::Approved,
    ]);

    // Simular que los días ya fueron consumidos
    $user->increment('used_vacation_days', 5);

    expect($user->fresh()->used_vacation_days)->toBe(5)
        ->and($user->fresh()->availableVacationDays())->toBe(15);

    // Eliminar la ausencia
    app(DeleteAbsence::class)->execute($absence);

    // Los días deben ser restaurados
    expect($user->fresh()->used_vacation_days)->toBe(0)
        ->and($user->fresh()->availableVacationDays())->toBe(20);
});

test('deleting a sick leave absence does not affect vacation days', function () {
    $user = User::factory()->create([
        'annual_vacation_days' => 20,
        'used_vacation_days' => 5,
    ]);

    // Crear una ausencia de enfermedad aprobada
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Sick,
        'start_date' => '2025-12-01',
        'end_date' => '2025-12-03',
        'status' => AbsenceStatus::Approved,
    ]);

    expect($user->fresh()->used_vacation_days)->toBe(5);

    // Eliminar la ausencia
    app(DeleteAbsence::class)->execute($absence);

    // Los días de vacaciones no deben cambiar
    expect($user->fresh()->used_vacation_days)->toBe(5)
        ->and($user->fresh()->availableVacationDays())->toBe(15);
});

test('deleting a pending vacation absence does not affect vacation days', function () {
    $user = User::factory()->create([
        'annual_vacation_days' => 20,
        'used_vacation_days' => 5,
    ]);

    // Crear una ausencia de vacaciones PENDIENTE
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => '2025-12-01',
        'end_date' => '2025-12-05',
        'status' => AbsenceStatus::Pending,
    ]);

    expect($user->fresh()->used_vacation_days)->toBe(5);

    // Eliminar la ausencia
    app(DeleteAbsence::class)->execute($absence);

    // Los días de vacaciones no deben cambiar porque la ausencia estaba pendiente
    expect($user->fresh()->used_vacation_days)->toBe(5)
        ->and($user->fresh()->availableVacationDays())->toBe(15);
});

test('deleting a rejected vacation absence does not affect vacation days', function () {
    $user = User::factory()->create([
        'annual_vacation_days' => 20,
        'used_vacation_days' => 5,
    ]);

    // Crear una ausencia de vacaciones RECHAZADA
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => '2025-12-01',
        'end_date' => '2025-12-05',
        'status' => AbsenceStatus::Rejected,
    ]);

    expect($user->fresh()->used_vacation_days)->toBe(5);

    // Eliminar la ausencia
    app(DeleteAbsence::class)->execute($absence);

    // Los días de vacaciones no deben cambiar porque la ausencia estaba rechazada
    expect($user->fresh()->used_vacation_days)->toBe(5)
        ->and($user->fresh()->availableVacationDays())->toBe(15);
});
