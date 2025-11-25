<div x-data="{ 
    show: false, 
    itemId: null 
}"
    @confirm-delete.window="
        show = true;
        itemId = $event.detail.id || $event.detail[0]?.id;
    "
    @keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
         @click="show = false"
         x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.away="show = false">
            
            <div class="p-6">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Confirmar Eliminación
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ¿Está seguro de que desea eliminar este elemento? Esta acción no se puede deshacer.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-5 flex gap-3">
                    <button type="button"
                            @click="show = false"
                            class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                        Cancelar
                    </button>
                    <button type="button"
                            @click="$dispatch('confirmed-delete', { id: itemId }); show = false"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
