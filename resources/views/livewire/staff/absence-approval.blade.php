<div>
    @if($showModal && $absence)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }" x-show="show" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"
                     wire:click="closeModal"></div>

                <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $action === 'approve' ? 'Aprobar' : 'Rechazar' }} Ausencia
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Empleado:</strong> {{ $absence->user->name }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Tipo:</strong> 
                            @if($absence->type === 'vacation') Vacaciones
                            @elseif($absence->type === 'sick') Enfermedad
                            @elseif($absence->type === 'personal') Personal
                            @else Otro @endif
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Periodo:</strong> {{ $absence->start_date->format('d/m/Y') }} - {{ $absence->end_date->format('d/m/Y') }} ({{ $absence->durationInDays() }} días)</p>
                        
                        @if($action === 'approve' && $absence->type === 'vacation')
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                <strong>Días disponibles:</strong> {{ $absence->user->availableVacationDays() }} / {{ $absence->user->annual_vacation_days }}
                            </p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notas (opcional)
                        </label>
                        <textarea wire:model="notes" rows="3"
                                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Agregar comentarios sobre la decisión..."></textarea>
                        @error('notes') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="closeModal" type="button"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            Cancelar
                        </button>
                        <button wire:click="submit" type="button"
                                class="px-4 py-2 {{ $action === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-md transition">
                            {{ $action === 'approve' ? 'Aprobar' : 'Rechazar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
