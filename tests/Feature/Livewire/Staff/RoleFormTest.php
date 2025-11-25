<?php

use App\Livewire\Staff\RoleForm;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create permissions
    Permission::create(['name' => 'view-dashboard']);
    Permission::create(['name' => 'view-shifts']);
    Permission::create(['name' => 'manage-roles']);

    // Create admin role and assign all permissions
    $adminRole = Role::create(['name' => 'administrador']);
    $adminRole->givePermissionTo(Permission::all());

    $this->admin = User::factory()->create();
    $this->admin->assignRole('administrador');
});

test('admin can create new role', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleForm::class)
        ->set('name', 'supervisor')
        ->set('selectedPermissions', ['view-dashboard', 'view-shifts'])
        ->call('save')
        ->assertDispatched('toast', function ($name, $data) {
            return $data['type'] === 'success';
        });

    $role = Role::where('name', 'supervisor')->first();
    expect($role)->not->toBeNull();
    expect($role->permissions->pluck('name')->toArray())->toContain('view-dashboard', 'view-shifts');
});

test('admin can edit existing role', function () {
    $role = Role::create(['name' => 'test-role']);
    $role->givePermissionTo('view-dashboard');

    Livewire::actingAs($this->admin)
        ->test(RoleForm::class, ['roleId' => $role->id])
        ->assertSet('name', 'test-role')
        ->assertSet('selectedPermissions', ['view-dashboard'])
        ->set('name', 'updated-role')
        ->set('selectedPermissions', ['view-dashboard', 'view-shifts'])
        ->call('save')
        ->assertDispatched('toast', function ($name, $data) {
            return $data['type'] === 'success';
        });

    $role->refresh();
    expect($role->name)->toBe('updated-role');
    expect($role->permissions->count())->toBe(2);
});

test('role name is required', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleForm::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('role name must be unique', function () {
    Role::create(['name' => 'existing-role']);

    Livewire::actingAs($this->admin)
        ->test(RoleForm::class)
        ->set('name', 'existing-role')
        ->call('save')
        ->assertHasErrors(['name' => 'unique']);
});

test('can toggle all permissions', function () {
    $allPermissions = Permission::pluck('name')->toArray();

    Livewire::actingAs($this->admin)
        ->test(RoleForm::class)
        ->call('toggleAllPermissions')
        ->assertSet('selectedPermissions', $allPermissions)
        ->call('toggleAllPermissions')
        ->assertSet('selectedPermissions', []);
});
