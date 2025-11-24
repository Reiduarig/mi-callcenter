<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" wire:model.defer="first_name" class="form-control">
            @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" wire:model.defer="last_name" class="form-control">
            @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" wire:model.defer="email" class="form-control">
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Código del empleado</label>
            <input type="text" wire:model.defer="employee_code" class="form-control">
            @error('employee_code') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Posición</label>
            <input type="text" wire:model.defer="position" class="form-control">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" wire:model.defer="is_active" class="form-check-input">
            <label class="form-check-label">Activo</label>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>
