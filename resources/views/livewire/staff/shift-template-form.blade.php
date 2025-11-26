<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-bold mb-6">
                    {{ $templateId ? 'Editar' : 'Nueva' }} Plantilla de Turno
                </h2>

                <form wire:submit="save">
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">
                                Nombre de la Plantilla <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model="name"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Ej: Mañana, Tarde, Noche">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Times -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium mb-2">
                                    Hora Inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="start_time" wire:model.live="start_time"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium mb-2">
                                    Hora Fin <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="end_time" wire:model.live="end_time"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Duration Preview -->
                        @if($start_time && $end_time)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-3">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Duración calculada: <strong>{{ $this->calculatedDuration }} horas</strong>
                                    @if($this->isOvernightShift)
                                        <span class="ml-2 text-xs">(turno nocturno)</span>
                                    @endif
                                </p>
                            </div>
                        @endif

                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="color" wire:model.live="color"
                                       class="h-10 w-20 rounded border-gray-300 dark:border-gray-600 cursor-pointer">
                                <input type="text" wire:model="color"
                                       class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="#3B82F6">
                                <div class="w-16 h-10 rounded border-2 dark:border-gray-600"
                                     style="background-color: {{ $color ?? '#3B82F6' }}"></div>
                            </div>
                            @error('color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium mb-2">
                                Descripción
                            </label>
                            <textarea id="description" wire:model="description" rows="3"
                                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Detalles adicionales sobre esta plantilla..."></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-sm font-medium mb-2">
                                Orden de visualización
                            </label>
                            <input type="number" id="sort_order" wire:model="sort_order" min="0"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="0">
                            <p class="text-xs text-gray-500 mt-1">Menor número aparece primero</p>
                            @error('sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Active -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" wire:model="is_active"
                                   class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <label for="is_active" class="ml-2 block text-sm">
                                Plantilla activa (disponible para asignación)
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                            <a href="{{ route('staff.shift-templates.index') }}" wire:navigate
                               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 text-sm dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition disabled:opacity-50"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ $templateId ? 'Actualizar' : 'Crear' }} Plantilla</span>
                                <span wire:loading>Guardando...</span>
                            </button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
