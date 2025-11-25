<div class="py-6" x-on:confirmed-delete.window="$wire.delete($event.detail.id)">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Turnos</h2>
                    <div class="flex gap-3">
                        <a href="{{ route('staff.shifts.calendar') }}" wire:navigate
                           class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Ver Calendario
                        </a>
                        <a href="{{ route('staff.shifts.create') }}" wire:navigate
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            + Nuevo Turno
                        </a>
                    </div>
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Buscar por empleado..." 
                           class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                    <select wire:model.live="filterUserId" 
                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos los empleados</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Empleado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Horario
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($shifts as $shift)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium">
                                            {{ $shift->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $shift->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $shift->start_time }} - {{ $shift->end_time }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('staff.shifts.edit', $shift->id) }}"  wire:navigate
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                            Editar
                                        </a>
                                        <button wire:click="confirmDelete({{ $shift->id }})" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No se encontraron turnos
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
