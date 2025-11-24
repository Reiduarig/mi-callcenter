<div>
    <div class="mb-3 d-flex gap-2">
        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Buscar por empleado...">

        <select wire:model="filterEmployeeId" class="form-control">
            <option value="">Todos los empleados</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
            @endforeach
        </select>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Tipo</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $absence)
                <tr>
                    <td>{{ $absence->employee->first_name }} {{ $absence->employee->last_name }}</td>
                    <td>{{ ucfirst($absence->type) }}</td>
                    <td>{{ $absence->start_date->format('d/m/Y') }}</td>
                    <td>{{ $absence->end_date->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($absence->status) }}</td>
                    <td>
                        <button wire:click="$emit('editAbsence', {{ $absence->id }})" class="btn btn-sm btn-primary">Editar</button>
                        <button wire:click="$emit('deleteAbsence', {{ $absence->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $absences->links() }}
</div>
