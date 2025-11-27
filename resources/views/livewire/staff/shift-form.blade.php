<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        {{ $shiftId ? 'Editar Turno' : 'Crear Turno' }}
                    </h2>
                    <a href="{{ route('staff.shifts.index') }}"  wire:navigate
                       class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>

                @error('general')
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                @enderror

                <form wire:submit.prevent="save" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Usuario *</label>
                        <select wire:model="user_id" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seleccionar empleado</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Plantilla de Turno</label>
                        <select wire:model.live="shift_template_id" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Sin plantilla (horario manual)</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">
                                    <span style="color: {{ $template->color }}">●</span>
                                    {{ $template->name }} ({{ substr($template->start_time, 0, 5) }} - {{ substr($template->end_time, 0, 5) }})
                                </option>
                            @endforeach
                        </select>
                        @error('shift_template_id') 
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                        
                        @if($shift_template_id)
                            <div class="mt-3 flex items-center">
                                <input type="checkbox" id="is_custom" wire:model.live="is_custom"
                                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="is_custom" class="ml-2 block text-sm">
                                    Personalizar horarios para este turno específico
                                </label>
                            </div>
                        @endif
                    </div>

                    @if(!$shiftId && $shift_template_id)
                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center">
                                <input type="checkbox" id="use_date_range" wire:model.live="use_date_range"
                                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="use_date_range" class="ml-2 block text-sm font-medium">
                                    Crear turnos para múltiples días (rango de fechas)
                                </label>
                            </div>
                            @if($use_date_range)
                                <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                                    Se crearán turnos con la plantilla "{{ $templates->firstWhere('id', $shift_template_id)?->name }}" para cada día en el rango.
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-{{ $use_date_range ? '2' : '3' }} gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Fecha {{ $use_date_range ? 'inicial' : '' }} *</label>
                            <input type="date" 
                                   wire:model="date" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('date') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        @if($use_date_range)
                            <div>
                                <label class="block text-sm font-medium mb-2">Fecha final *</label>
                                <input type="date" 
                                       wire:model="end_date" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('end_date') 
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                                @enderror
                            </div>
                        @endif

                        @if(!$use_date_range)
                            <div>
                                <label class="block text-sm font-medium mb-2">Hora de inicio *</label>
                                <input type="time" 
                                       wire:model="start_time"
                                       {{ $shift_template_id && !$is_custom ? 'readonly' : '' }}
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $shift_template_id && !$is_custom ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                                @error('start_time') 
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Hora de fin *</label>
                                <input type="time" 
                                       wire:model="end_time"
                                       {{ $shift_template_id && !$is_custom ? 'readonly' : '' }}
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $shift_template_id && !$is_custom ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                                @error('end_time') 
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                                @enderror
                            </div>
                        @endif
                    </div>

                    @if($use_date_range)
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="exclude_weekends" wire:model.live="exclude_weekends"
                                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="exclude_weekends" class="ml-2 block text-sm font-medium">
                                    Excluir fines de semana (sábados y domingos)
                                </label>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Se crearán turnos para todos los días {{ $exclude_weekends ? 'laborables (L-V)' : '(L-D)' }} en el rango seleccionado.
                            </p>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                        <a href="{{ route('staff.shifts.index') }}"  wire:navigate
                           class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 text-sm dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
                            {{ $shiftId ? 'Actualizar' : 'Crear' }} Turno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>