<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">Plantillas de Turnos</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gestiona plantillas reutilizables para asignación rápida</p>
                    </div>
                    @can('manage-shift-templates')
                        <a href="{{ route('staff.shift-templates.create') }}" wire:navigate
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nueva Plantilla
                        </a>
                    @endcan
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($templates as $template)
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 border-l-4 hover:shadow-lg transition"
                             style="border-left-color: {{ $template->color }}">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $template->color }}"></div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $template->name }}</h3>
                                </div>
                                
                                <button wire:click="toggleActive({{ $template->id }})"
                                        class="text-sm px-2 py-1 rounded {{ $template->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                    {{ $template->is_active ? 'Activa' : 'Inactiva' }}
                                </button>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <strong class="mr-2">Horario:</strong> {{ substr($template->start_time, 0, 5) }} - {{ substr($template->end_time, 0, 5) }}
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <strong class="mr-2">Duración:</strong> {{ $template->durationInHours() }} horas
                                </div>

                                @if($template->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $template->description }}</p>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t dark:border-gray-600">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $template->shifts()->count() }} turnos asignados
                                </span>
                                
                                @can('manage-shift-templates')
                                    <div class="flex gap-2">
                                        <a href="{{ route('staff.shift-templates.edit', $template->id) }}" wire:navigate
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                            Editar
                                        </a>
                                        @if($template->shifts()->count() === 0)
                                            <button wire:click="delete({{ $template->id }})"
                                                    wire:confirm="¿Estás seguro de eliminar esta plantilla?"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                                Eliminar
                                            </button>
                                        @endif
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No hay plantillas configuradas</p>
                            <a href="{{ route('staff.shift-templates.create') }}" wire:navigate
                               class="mt-4 inline-block text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                Crear primera plantilla
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
