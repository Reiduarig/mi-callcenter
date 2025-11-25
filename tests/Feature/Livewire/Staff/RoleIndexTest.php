<?php

use App\Livewire\Staff\RoleIndex;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create permissions
    Permission::create(['name' => 'view-dashboard']);
    Permission::create(['name' => 'manage-roles']);
    Permission::create(['name' => 'view-shifts']);

    // Create admin role and assign all permissions
    $adminRole = Role::create(['name' => 'administrador']);
    $adminRole->givePermissionTo(Permission::all());

    $this->admin = User::factory()->create();
    $this->admin->assignRole('administrador');
});

test('admin can view roles index', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleIndex::class)
        ->assertOk()
        ->assertSee('Nuevo Rol'); // El header está presente
});

test('roles are displayed with user and permission counts', function () {
    $role = Role::create(['name' => 'test-role']);
    $role->givePermissionTo('view-dashboard');

    $user = User::factory()->create();
    $user->assignRole('test-role');

    Livewire::actingAs($this->admin)
        ->test(RoleIndex::class)
        ->assertSee('Test-role') // ucfirst() aplicado en vista
        ->assertSee('1 usuario')
        ->assertSee('1 permiso');
});

test('can search roles', function () {
    Role::create(['name' => 'supervisor']);
    Role::create(['name' => 'analista']);

    Livewire::actingAs($this->admin)
        ->test(RoleIndex::class)
        ->set('search', 'supervisor')
        ->assertSee('Supervisor') // ucfirst() aplicado en vista
        ->assertDontSee('Analista');
});

test('cannot delete system roles', function () {
    $role = Role::where('name', 'administrador')->first();

    Livewire::actingAs($this->admin)
        ->test(RoleIndex::class)
        ->call('delete', $role->id)
        ->assertDispatched('toast', function ($name, $data) {
            return $data['type'] === 'error' && str_contains($data['message'], 'rol del sistema');
        });

    expect(Role::where('name', 'administrador')->exists())->toBeTrue();
});

test('cannot delete role with assigned users', function () {
    $role = Role::create(['name' => 'test-role']);
    $user = User::factory()->create();
    $user->assignRole('test-role');

    Livewire::actingAs($this->admin)
        ->test(RoleIndex::class)
        ->call('delete', $role->id)
        ->assertDispatched('toast', function ($name, $data) {
            return $data['type'] === 'error' && str_contains($data['message'], 'asignado a usuarios');
        });

    expect(Role::where('name', 'test-role')->exists())->toBeTrue();
});

test('can delete custom role without users', function () {
    $role = Role::create(['name' => 'test-role']);

    Livewire::actingAs($this->admin)
        ->test(RoleIndex::class)
        ->call('delete', $role->id)
        ->assertDispatched('toast', function ($name, $data) {
            return $data['type'] === 'success';
        });

    expect(Role::where('name', 'test-role')->exists())->toBeFalse();
});
