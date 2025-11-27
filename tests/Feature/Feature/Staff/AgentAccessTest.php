<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Crear roles y permisos
    $agenteRole = Role::create(['name' => 'agente']);

    Permission::create(['name' => 'view-own-shifts']);
    Permission::create(['name' => 'view-own-calendar']);
    Permission::create(['name' => 'view-own-absences']);
    Permission::create(['name' => 'create-own-absences']);

    $agenteRole->givePermissionTo([
        'view-own-shifts',
        'view-own-calendar',
        'view-own-absences',
        'create-own-absences',
    ]);
});

test('agente can access shifts index', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $response = $this->actingAs($agente)->get(route('staff.shifts.index'));

    $response->assertOk();
});

test('agente can access calendar', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $response = $this->actingAs($agente)->get(route('staff.shifts.calendar'));

    $response->assertOk();
});

test('agente can access absences index', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $response = $this->actingAs($agente)->get(route('staff.absences.index'));

    $response->assertOk();
});

test('agente can access absence create form', function () {
    $agente = User::factory()->create();
    $agente->assignRole('agente');

    $response = $this->actingAs($agente)->get(route('staff.absences.create'));

    $response->assertOk();
});
