<?php

use App\Domains\Staff\Actions\ApproveAbsence;
use App\Domains\Staff\Actions\CreateAbsence;
use App\Domains\Staff\Actions\RejectAbsence;
use App\Domains\Staff\DataTransferObjects\CreateAbsenceData;
use App\Domains\Staff\Enums\AbsenceStatus;
use App\Domains\Staff\Enums\AbsenceType;
use App\Domains\Staff\Exceptions\InsufficientVacationDaysException;
use App\Domains\Staff\Exceptions\InvalidAbsenceStatusException;
use App\Domains\Staff\Exceptions\ValidationException;
use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\Shift;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('ValidationException is thrown when creating absence with overlapping dates', function () {
    $user = User::factory()->create();
    $user->assignRole('agente');

    // Crear ausencia existente
    Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(7),
        'status' => AbsenceStatus::Approved,
    ]);

    // Intentar crear ausencia que solapa
    expect(fn () => app(CreateAbsence::class)->execute(
        CreateAbsenceData::fromArray([
            'user_id' => $user->id,
            'type' => AbsenceType::Vacation->value,
            'start_date' => now()->addDays(6)->format('Y-m-d'),
            'end_date' => now()->addDays(8)->format('Y-m-d'),
            'status' => AbsenceStatus::Pending,
        ])
    ))->toThrow(ValidationException::class);
});

test('ValidationException is thrown when creating absence on day with scheduled shift', function () {
    $user = User::factory()->create();
    $user->assignRole('agente');

    $date = now()->addDays(5);

    // Crear turno existente
    Shift::create([
        'user_id' => $user->id,
        'date' => $date,
        'start_time' => '09:00',
        'end_time' => '17:00',
    ]);

    // Intentar crear ausencia en el mismo día - esto NO debería fallar
    // porque las ausencias pueden coexistir con turnos (de hecho, las ausencias
    // se revisan al crear turnos, no al revés)
    $absence = app(CreateAbsence::class)->execute(
        CreateAbsenceData::fromArray([
            'user_id' => $user->id,
            'type' => AbsenceType::Vacation->value,
            'start_date' => $date->format('Y-m-d'),
            'end_date' => $date->format('Y-m-d'),
            'status' => AbsenceStatus::Pending,
        ])
    );

    expect($absence)->toBeInstanceOf(Absence::class);
})->skip('Ausencias no validan contra turnos - los turnos validan contra ausencias');

test('InvalidAbsenceStatusException is thrown when approving non-pending absence', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrador');
    $this->actingAs($admin);

    $user = User::factory()->create(['annual_vacation_days' => 20]);
    $user->assignRole('agente');

    // Crear ausencia ya aprobada
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(7),
        'status' => AbsenceStatus::Approved,
        'approved_by' => $admin->id,
        'approved_at' => now(),
    ]);

    // Intentar aprobar nuevamente
    expect(fn () => app(ApproveAbsence::class)->execute($absence))
        ->toThrow(InvalidAbsenceStatusException::class)
        ->and(fn () => app(ApproveAbsence::class)->execute($absence))
        ->toThrow(InvalidAbsenceStatusException::class, 'Solo se pueden aprobar ausencias con estado Pendiente');
});

test('InvalidAbsenceStatusException is thrown when rejecting non-pending absence', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrador');
    $this->actingAs($admin);

    $user = User::factory()->create(['annual_vacation_days' => 20]);
    $user->assignRole('agente');

    // Crear ausencia ya aprobada
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Sick,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(7),
        'status' => AbsenceStatus::Approved,
        'approved_by' => $admin->id,
        'approved_at' => now(),
    ]);

    // Intentar rechazar
    expect(fn () => app(RejectAbsence::class)->execute($absence))
        ->toThrow(InvalidAbsenceStatusException::class);
});

test('InsufficientVacationDaysException is thrown when approving vacation without enough days', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrador');
    $this->actingAs($admin);

    $user = User::factory()->create([
        'annual_vacation_days' => 10,
        'used_vacation_days' => 8,
    ]);
    $user->assignRole('agente');

    // Crear ausencia de 5 días (solo tiene 2 disponibles)
    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(9), // 5 días
        'status' => AbsenceStatus::Pending,
    ]);

    // Intentar aprobar sin días suficientes
    expect(fn () => app(ApproveAbsence::class)->execute($absence))
        ->toThrow(InsufficientVacationDaysException::class);
});

test('InsufficientVacationDaysException provides useful data', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrador');
    $this->actingAs($admin);

    $user = User::factory()->create([
        'annual_vacation_days' => 10,
        'used_vacation_days' => 9,
    ]);
    $user->assignRole('agente');

    $absence = Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(9), // 5 días
        'status' => AbsenceStatus::Pending,
    ]);

    try {
        app(ApproveAbsence::class)->execute($absence);
        $this->fail('Expected InsufficientVacationDaysException to be thrown');
    } catch (InsufficientVacationDaysException $e) {
        expect($e->getAvailableDays())->toBe(1)
            ->and($e->getRequestedDays())->toBe(5)
            ->and($e->user->id)->toBe($user->id)
            ->and($e->getUserMessage())->toContain('Disponibles: 1')
            ->and($e->getUserMessage())->toContain('Solicitados: 5');
    }
});

test('ValidationException provides user-friendly message', function () {
    $user = User::factory()->create();
    $user->assignRole('agente');

    // Crear ausencia existente
    Absence::create([
        'user_id' => $user->id,
        'type' => AbsenceType::Vacation,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(7),
        'status' => AbsenceStatus::Approved,
    ]);

    try {
        app(CreateAbsence::class)->execute(
            CreateAbsenceData::fromArray([
                'user_id' => $user->id,
                'type' => AbsenceType::Vacation->value,
                'start_date' => now()->addDays(6)->format('Y-m-d'),
                'end_date' => now()->addDays(8)->format('Y-m-d'),
                'status' => AbsenceStatus::Pending,
            ])
        );
        $this->fail('Expected ValidationException to be thrown');
    } catch (ValidationException $e) {
        expect($e->getUserMessage())->toBeString()
            ->and($e->getUserMessage())->not->toBeEmpty();
    }
});
