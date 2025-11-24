<div>
    <div class="mb-3">
        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Buscar empleados...">
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Código</th>
                <th>Posición</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->employee_code }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->is_active ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <button wire:click="$emit('editEmployee', {{ $employee->id }})" class="btn btn-sm btn-primary">Editar</button>
                        <button wire:click="$emit('deleteEmployee', {{ $employee->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $employees->links() }}
</div>