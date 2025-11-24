<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>Empleado</label>
            <select wire:model="employeeId" class="form-control">
                <option value="">Seleccione un empleado</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                @endforeach
            </select>
            @error('employeeId') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" wire:model="date" class="form-control">
            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Hora de inicio</label>
            <input type="time" wire:model="start_time" class="form-control">
            @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Hora de fin</label>
            <input type="time" wire:model="end_time" class="form-control">
            @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select wire:model="status" class="form-control">
                <option value="scheduled">Programado</option>
                <option value="completed">Completado</option>
                <option value="cancelled">Cancelado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar turno</button>
    </form>
</div>