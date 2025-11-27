<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        {{ $absenceId ? 'Editar Ausencia' : 'Crear Ausencia' }}
                    </h2>
                    <a href="{{ route('staff.absences.index') }}" wire:navigate
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
                    @if($canEditUser)
                        <div>
                            <label class="block text-sm font-medium mb-2">Usuario *</label>
                            <select wire:model="userId" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('userId') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    @else
                        <!-- Campo oculto para agentes -->
                        <input type="hidden" wire:model="userId">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-blue-800 dark:text-blue-200">
                                    Esta ausencia será registrada para: <strong>{{ auth()->user()->name }}</strong>
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Tipo de ausencia *</label>
                            <select wire:model.live="type" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($typeOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        @if($canEditStatus)
                            <div>
                                <label class="block text-sm font-medium mb-2">Estado *</label>
                                <select wire:model="status" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status') 
                                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                                @enderror
                            </div>
                        @else
                            <!-- Campo oculto para agentes -->
                            <input type="hidden" wire:model="status" value="pending">
                            <div>
                                <label class="block text-sm font-medium mb-2">Estado</label>
                                <div class="px-4 py-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                                    <span class="text-sm text-yellow-800 dark:text-yellow-200">
                                        🕐 Pendiente de aprobación
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($type === 'vacation')
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                        Días de vacaciones disponibles
                                    </p>
                                    <p class="text-lg font-bold text-green-900 dark:text-green-100 mt-1">
                                        {{ auth()->user()->availableVacationDays() }} días disponibles
                                    </p>
                                    <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                                        ({{ auth()->user()->used_vacation_days }} usados de {{ auth()->user()->annual_vacation_days }} anuales)
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Fecha de inicio *</label>
                            <input type="date" 
                                   wire:model="start_date" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('start_date') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Fecha de fin *</label>
                            <input type="date" 
                                   wire:model="end_date" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('end_date') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                        <a href="{{ route('staff.absences.index') }}" wire:navigate 
                           class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 text-sm dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
                            {{ $absenceId ? 'Actualizar' : 'Crear' }} Ausencia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
