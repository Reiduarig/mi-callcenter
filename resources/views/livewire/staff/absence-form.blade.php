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
            <label>Tipo de ausencia</label>
            <select wire:model="type" class="form-control">
                <option value="vacation">Vacaciones</option>
                <option value="sick">Enfermedad</option>
                <option value="personal">Personal</option>
                <option value="other">Otro</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Fecha de inicio</label>
            <input type="date" wire:model="start_date" class="form-control">
            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Fecha de fin</label>
            <input type="date" wire:model="end_date" class="form-control">
            @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select wire:model="status" class="form-control">
                <option value="approved">Aprobado</option>
                <option value="pending">Pendiente</option>
                <option value="rejected">Rechazado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar ausencia</button>
    </form>
</div>
