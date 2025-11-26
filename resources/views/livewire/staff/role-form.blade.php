<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form wire:submit="save">
                    <!-- Nombre del rol -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre del Rol <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                                wire:model="name" 
                                id="name"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Ej: supervisor, analista, etc.">
                        @error('name') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Permisos -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Permisos
                            </label>
                            <button type="button"
                                    wire:click="toggleAllPermissions"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                {{ count($selectedPermissions) === $permissions->flatten()->count() ? 'Deseleccionar todos' : 'Seleccionar todos' }}
                            </button>
                        </div>

                        <div class="space-y-6">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 capitalize">
                                        {{ ucfirst($module) }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($modulePermissions as $permission)
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" 
                                                        wire:model="selectedPermissions" 
                                                        value="{{ $permission->name }}"
                                                        class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ str_replace('-', ' ', $permission->name) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('staff.roles.index') }}" 
                            wire:navigate
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 text-sm dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ $roleId ? 'Actualizar' : 'Crear' }} Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

