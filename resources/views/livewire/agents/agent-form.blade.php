<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>Usuario</label>
            <select wire:model="userId" class="form-control">
                <option value="">Seleccione un usuario</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            @error('userId') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Código del agente</label>
            <input type="text" wire:model="employee_code" class="form-control">
            @error('employee_code') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select wire:model="status" class="form-control">
                <option value="available">Disponible</option>
                <option value="on_call">En llamada</option>
                <option value="paused">Pausado</option>
                <option value="offline">Offline</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Skill Group</label>
            <input type="text" wire:model="skill_group" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>
