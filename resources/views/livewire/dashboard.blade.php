<div class="space-y-6">
    <!-- Stats Grid -->
    <div>
        @if(auth()->user()->hasRole(['administrador', 'gerente']))
            <!-- Dashboard para Administradores y Gerentes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Total Employees -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Empleados</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalEmployees }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span class="text-green-600 font-semibold">{{ $activeEmployees }}</span> activos
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Agents -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Agentes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalAgents }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            con rol de agente
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Shifts Today -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Turnos Hoy</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $scheduledShiftsToday }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span class="text-orange-600 font-semibold">{{ $onLeaveEmployees }}</span> en ausencia
                            @if($pendingAbsences > 0)
                                <span class="mx-2">•</span>
                                <span class="text-yellow-600 font-semibold">{{ $pendingAbsences }}</span> pendientes
                            @endif
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Absences -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ausencias Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingAbsences }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            requieren aprobación
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @elseif(auth()->user()->hasRole('coordinador'))
            <!-- Dashboard para Coordinadores -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Total Employees -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Empleados</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalEmployees }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span class="text-green-600 font-semibold">{{ $activeEmployees }}</span> activos
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Shifts Today -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Turnos Hoy</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $scheduledShiftsToday }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            turnos programados
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Absences -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ausencias Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingAbsences }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            requieren revisión
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @else
            <!-- Dashboard para Agentes -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-8 text-white mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Bienvenido, {{ auth()->user()->name }}!</h2>
                    <p class="text-indigo-100">Rol: {{ ucfirst(auth()->user()->roles->first()->name ?? 'Usuario') }}</p>
                </div>
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- My Shifts Today -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mis Turnos Hoy</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $scheduledShiftsToday }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            turnos asignados
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- My Pending Absences -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mis Ausencias Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingAbsences }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            en revisión
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Vacation Days -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Días de Vacaciones</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $availableVacationDays }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span class="text-red-600 font-semibold">{{ $usedVacationDays }}</span> usados de 
                            <span class="text-green-600 font-semibold">{{ $annualVacationDays }}</span>
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if(auth()->user()->hasRole(['administrador', 'gerente']))
            <!-- Acciones para Administradores y Gerentes -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-3">
                    @can('view-users')
                    <a href="{{ route('staff.users.create') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800 dark:hover:to-blue-700 transition">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Nuevo Usuario</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Registrar usuario</p>
                        </div>
                    </a>
                    @endcan

                    @can('view-shifts')
                    <a href="{{ route('staff.shifts.create') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 hover:from-purple-100 hover:to-purple-200 dark:hover:from-purple-800 dark:hover:to-purple-700 transition">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Asignar Turno</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Crear nuevo turno</p>
                        </div>
                    </a>
                    @endcan

                    @can('view-calendar')
                    <a href="{{ route('staff.shifts.calendar') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 hover:from-green-100 hover:to-green-200 dark:hover:from-green-800 dark:hover:to-green-700 transition">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Ver Calendario</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Planificación mensual</p>
                        </div>
                    </a>
                    @endcan

                    @can('view-absences')
                    <a href="{{ route('staff.absences.index') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 hover:from-yellow-100 hover:to-yellow-200 dark:hover:from-yellow-800 dark:hover:to-yellow-700 transition">
                        <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Revisar Ausencias</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                @if($pendingAbsences > 0)
                                    {{ $pendingAbsences }} pendiente{{ $pendingAbsences > 1 ? 's' : '' }}
                                @else
                                    Sin pendientes
                                @endif
                            </p>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>

        @elseif(auth()->user()->hasRole('coordinador'))
            <!-- Acciones para Coordinadores -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-3">
                    @can('view-shifts')
                    <a href="{{ route('staff.shifts.index') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 hover:from-purple-100 hover:to-purple-200 dark:hover:from-purple-800 dark:hover:to-purple-700 transition">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Gestionar Turnos</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Ver y editar turnos</p>
                        </div>
                    </a>
                    @endcan

                    @can('view-calendar')
                    <a href="{{ route('staff.shifts.calendar') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 hover:from-green-100 hover:to-green-200 dark:hover:from-green-800 dark:hover:to-green-700 transition">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Ver Calendario</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Planificación general</p>
                        </div>
                    </a>
                    @endcan

                    @can('view-users')
                    <a href="{{ route('staff.users.index') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800 dark:hover:to-blue-700 transition">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Ver Personal</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Lista de empleados</p>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>

        @else
            <!-- Acciones para Agentes -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Mis Acciones
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('staff.shifts.calendar') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 hover:from-indigo-100 hover:to-indigo-200 dark:hover:from-indigo-800 dark:hover:to-indigo-700 transition">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Mi Calendario</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Ver mis turnos</p>
                        </div>
                    </a>

                    <a href="{{ route('staff.absences.create') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 hover:from-yellow-100 hover:to-yellow-200 dark:hover:from-yellow-800 dark:hover:to-yellow-700 transition">
                        <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Solicitar Ausencia</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Nueva solicitud</p>
                        </div>
                    </a>

                    <a href="{{ route('staff.absences.index') }}" wire:navigate
                       class="flex items-center p-3 rounded-lg bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 hover:from-green-100 hover:to-green-200 dark:hover:from-green-800 dark:hover:to-green-700 transition">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Mis Ausencias</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                @if($pendingAbsences > 0)
                                    {{ $pendingAbsences }} pendiente{{ $pendingAbsences > 1 ? 's' : '' }}
                                @else
                                    Historial completo
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        <!-- Recent Shifts -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Próximos Turnos
                </span>
                <a href="{{ route('staff.shifts.index') }}" wire:navigate  class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                    Ver todos →
                </a>
            </h3>

            @if(count($recentShifts) > 0)
                <div class="space-y-3">
                    @foreach($recentShifts as $shift)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center space-x-4">
                                {{-- <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">
                                        {{ substr($shift['user']['name'], 0, 1) ?? 'U' }}
                                    </span>
                                </div> --}}
                                <div>
                                     @if(auth()->user()->hasRole(['administrador', 'gerente']))
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $shift['user']['name'] }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($shift['date'])->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($shift['start_time'])->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($shift['end_time'])->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">No hay turnos programados próximamente</p>
                    <a href="{{ route('staff.shifts.create') }}" wire:navigate class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Crear primer turno
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
