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
                <th>Fecha</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
                <tr>
                    <td>{{ $shift->employee->first_name }} {{ $shift->employee->last_name }}</td>
                    <td>{{ $shift->date->format('d/m/Y') }}</td>
                    <td>{{ $shift->start_time }}</td>
                    <td>{{ $shift->end_time }}</td>
                    <td>{{ $shift->status }}</td>
                    <td>
                        <button wire:click="$emit('editShift', {{ $shift->id }})" class="btn btn-sm btn-primary">Editar</button>
                        <button wire:click="$emit('deleteShift', {{ $shift->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shifts->links() }}
</div>
