<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">
                        {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
                    </h2>
                </div>

                <form wire:submit="save" class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">
                            Nombre completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name"
                               wire:model.defer="name" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email"
                               wire:model.defer="email" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Password -->
                    @if(!$userId)
                        <div>
                            <label for="password" class="block text-sm font-medium mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password"
                                   wire:model.defer="password" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium mb-2">
                                Confirmar contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation"
                                   wire:model.defer="password_confirmation" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    @else
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model.live="updatePassword"
                                       class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm">Actualizar contraseña</span>
                            </label>
                        </div>

                        @if($updatePassword)
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2">
                                    Nueva contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password"
                                       wire:model.defer="password" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('password') 
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium mb-2">
                                    Confirmar contraseña <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_confirmation"
                                       wire:model.defer="password_confirmation" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        @endif
                    @endif

                    <!-- Roles -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Roles <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @foreach($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model.defer="selectedRoles"
                                           value="{{ $role->id }}"
                                           class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm">{{ ucfirst($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedRoles') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Supervisor -->
                    <div>
                        <label for="supervisor_id" class="block text-sm font-medium mb-2">
                            Supervisor
                        </label>
                        <select id="supervisor_id"
                                wire:model.defer="supervisorId" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Sin supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                            @endforeach
                        </select>
                        @error('supervisorId') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Hired Date -->
                    <div>
                        <label for="hired_at" class="block text-sm font-medium mb-2">
                            Fecha de contratación
                        </label>
                        <input type="date" 
                               id="hired_at"
                               wire:model.defer="hiredAt" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('hiredAt') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Vacation Days -->
                    <div>
                        <label for="annual_vacation_days" class="block text-sm font-medium mb-2">
                            Días de vacaciones anuales
                        </label>
                        <input type="number" 
                               id="annual_vacation_days"
                               wire:model.defer="annualVacationDays" 
                               min="0"
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('annualVacationDays') 
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   wire:model.defer="isActive"
                                   class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm">Usuario activo</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            {{ $userId ? 'Actualizar' : 'Crear' }}
                        </button>
                        <a href="{{ route('staff.users.index') }}" wire:navigate
                           class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
