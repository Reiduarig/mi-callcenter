<div>
    <div class="mb-3">
        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Buscar agentes...">
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Código</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agents as $agent)
                <tr>
                    <td>{{ $agent->user->name }}</td>
                    <td>{{ $agent->user->email }}</td>
                    <td>{{ $agent->employee_code }}</td>
                    <td>{{ $agent->status }}</td>
                    <td>
                        <button wire:click="$emit('editAgent', {{ $agent->id }})" class="btn btn-sm btn-primary">Editar</button>
                        <button wire:click="$emit('deleteAgent', {{ $agent->id }})" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $agents->links() }}
</div>
