<?php

use App\Domains\Staff\Models\Absence;
use App\Livewire\Staff\AbsenceForm;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Crear roles y permisos
    $agenteRole = Role::create(['name' => 'agente']);
    $adminRole = Role::create(['name' => 'administrador']);

    Permission::create(['name' => 'view-own-absences']);
    Permission::create(['name' => 'create-own-absences']);
    Permission::create(['name' => 'edit-own-absences']);
    Permission::create(['name' => 'view-absences']);
    Permission::create(['name' => 'create-absences']);
    Permission::create(['name' => 'edit-absences']);

    $agenteRole->givePermissionTo([
        'view-own-absences',
        'create-own-absences',
        'edit-own-absences',
    ]);

    $adminRole->givePermissionTo([
        'view-absences',
        'create-absences',
        'edit-absences',
    ]);
});

test('agente can only create absence for themselves', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $otroUsuario = User::factory()->create();

    Livewire::actingAs($agente)
        ->test(AbsenceForm::class)
        ->set('userId', $otroUsuario->id) // Intentar asignar a otro usuario
        ->set('type', 'vacation')
        ->set('start_date', now()->addDays(1)->format('Y-m-d'))
        ->set('end_date', now()->addDays(3)->format('Y-m-d'))
        ->call('save');

    // Verificar que la ausencia se creó para el agente, no para el otro usuario
    $ausencia = Absence::latest()->first();
    expect($ausencia->user_id)->toBe($agente->id);
});

test('agente cannot self-approve absences', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    Livewire::actingAs($agente)
        ->test(AbsenceForm::class)
        ->set('status', 'approved') // Intentar auto-aprobar
        ->set('type', 'vacation')
        ->set('start_date', now()->addDays(1)->format('Y-m-d'))
        ->set('end_date', now()->addDays(3)->format('Y-m-d'))
        ->call('save');

    // Verificar que el estado se forzó a 'pending'
    $ausencia = Absence::latest()->first();
    expect($ausencia->status->value)->toBe('pending');
});

test('agente cannot edit other users absences', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $otroUsuario = User::factory()->create();

    $ausenciaOtro = Absence::create([
        'user_id' => $otroUsuario->id,
        'type' => 'sick',
        'start_date' => now()->addDays(1),
        'end_date' => now()->addDays(3),
        'reason' => 'Test',
        'status' => 'pending',
    ]);

    $this->actingAs($agente);

    $form = new AbsenceForm;

    // Debería lanzar excepción 403
    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    $form->loadAbsence($ausenciaOtro->id);
});

test('agente can edit their own absences', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $miAusencia = Absence::create([
        'user_id' => $agente->id,
        'type' => 'vacation',
        'start_date' => now()->addDays(1),
        'end_date' => now()->addDays(3),
        'reason' => 'Test',
        'status' => 'pending',
    ]);

    Livewire::actingAs($agente)
        ->test(AbsenceForm::class, ['absenceId' => $miAusencia->id])
        ->assertSet('userId', $agente->id)
        ->assertSet('status', 'pending')
        ->assertSet('type', 'vacation');
});

test('admin can create absence for any user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrador');

    $otroUsuario = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(AbsenceForm::class)
        ->set('userId', $otroUsuario->id)
        ->set('type', 'vacation')
        ->set('start_date', now()->addDays(1)->format('Y-m-d'))
        ->set('end_date', now()->addDays(3)->format('Y-m-d'))
        ->set('status', 'approved')
        ->call('save');

    // Verificar que la ausencia se creó para el otro usuario con el estado correcto
    $ausencia = Absence::latest()->first();
    expect($ausencia->user_id)->toBe($otroUsuario->id)
        ->and($ausencia->status->value)->toBe('approved');
});

test('agente sees only themselves in user selector', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    User::factory()->count(5)->create(); // Crear otros usuarios

    Livewire::actingAs($agente)
        ->test(AbsenceForm::class)
        ->assertViewHas('users', function ($users) use ($agente) {
            return $users->count() === 1 && $users->first()->id === $agente->id;
        });
});

test('agente cannot edit user field in form', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    Livewire::actingAs($agente)
        ->test(AbsenceForm::class)
        ->assertViewHas('canEditUser', false)
        ->assertViewHas('canEditStatus', false);
});

test('admin can edit user and status fields in form', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrador');

    Livewire::actingAs($admin)
        ->test(AbsenceForm::class)
        ->assertViewHas('canEditUser', true)
        ->assertViewHas('canEditStatus', true);
});
