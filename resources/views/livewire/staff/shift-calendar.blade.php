<div class="space-y-6">
    
    <!-- Calendar Controls -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            
            <!-- Month Navigation -->
            <div class="flex items-center space-x-4">
                <button wire:click="previousMonth" 
                        class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white min-w-[200px] text-center">
                    {{ $currentMonthName }} {{ $year }}
                </h2>

                <button wire:click="nextMonth" 
                        class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <button wire:click="goToToday" 
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Hoy
                </button>
            </div>

            <!-- Filters & Actions -->
            <div class="flex items-center space-x-4">
                <!-- Employee Filter -->
                <select wire:model.live="selectedUserId" 
                        class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los usuarios</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <!-- New Shift Button -->
                <a href="{{ route('staff.shifts.create') }}"  wire:navigate
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Turno
                </a>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                <span class="text-gray-600 dark:text-gray-400">Ausencias</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-indigo-200 dark:bg-indigo-900 rounded mr-2"></div>
                <span class="text-gray-600 dark:text-gray-400">Día actual</span>
            </div>
            <div class="flex items-center ml-auto">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
                <span class="text-gray-600 dark:text-gray-400 italic">Arrastra los turnos para moverlos</span>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        
        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
                <div class="py-3 px-2 text-center">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $day }}</span>
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 auto-rows-fr">
            @foreach($calendarDays as $day)
                <div x-data="{ 
                        draggingOver: false,
                        handleDragOver(e) {
                            e.preventDefault();
                            this.draggingOver = true;
                        },
                        handleDragLeave() {
                            this.draggingOver = false;
                        },
                        handleDrop(e) {
                            e.preventDefault();
                            this.draggingOver = false;
                            const shiftId = e.dataTransfer.getData('shiftId');
                            if (shiftId) {
                                @this.moveShift(parseInt(shiftId), '{{ $day['date'] }}');
                            }
                        }
                    }"
                    @dragover="handleDragOver($event)"
                    @dragleave="handleDragLeave()"
                    @drop="handleDrop($event)"
                    :class="{ 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/30': draggingOver }"
                    class="min-h-[120px] border-b border-r border-gray-200 dark:border-gray-700 p-2 
                    {{ !$day['isCurrentMonth'] ? 'bg-gray-50 dark:bg-gray-900' : '' }}
                    {{ $day['isToday'] ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}
                    {{ $day['isWeekend'] ? 'bg-gray-50 dark:bg-gray-900/50' : '' }}
                    hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    
                    <!-- Day Number -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold 
                            {{ !$day['isCurrentMonth'] ? 'text-gray-400 dark:text-gray-600' : 'text-gray-700 dark:text-gray-300' }}
                            {{ $day['isToday'] ? 'text-indigo-600 dark:text-indigo-400 text-base' : '' }}">
                            {{ $day['day'] }}
                        </span>
                        
                        @if($day['isToday'])
                            <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                        @endif
                    </div>

                    <!-- Shifts for this day -->
                    @if(isset($shiftsData[$day['date']]))
                        <div class="space-y-1">
                            @foreach($shiftsData[$day['date']] as $shift)
                                <div class="group relative"
                                     draggable="true"
                                     @dragstart="$event.dataTransfer.setData('shiftId', '{{ $shift['id'] }}'); $event.dataTransfer.effectAllowed = 'move';"
                                     @dragend="$event.target.style.opacity = '1';"
                                     style="opacity: 1; transition: opacity 0.2s;"
                                     onmousedown="this.style.cursor='grab';"
                                     onmouseup="this.style.cursor='pointer';">
                                    <div class="text-xs px-2 py-1.5 rounded cursor-move hover:shadow-md transition truncate border-l-4"
                                         style="border-left-color: {{ $shift['color'] }}; background-color: {{ $shift['color'] }}20; color: {{ $shift['color'] }};">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                            </svg>
                                            <div class="font-semibold truncate flex-1">{{ $shift['employee_name'] }}</div>
                                            @if($shift['is_custom'])
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" title="Horario personalizado">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="text-xs opacity-90 ml-4 font-medium">
                                            {{ \Carbon\Carbon::parse($shift['start_time'])->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($shift['end_time'])->format('H:i') }}
                                        </div>
                                    </div>
                                    
                                    <!-- Tooltip on hover -->
                                    <div class="absolute z-10 hidden group-hover:block bg-gray-900 text-white text-xs rounded-lg px-3 py-2 mt-1 whitespace-nowrap shadow-lg">
                                        <div class="font-semibold">{{ $shift['employee_name'] }}</div>
                                        @if($shift['template_name'])
                                            <div class="flex items-center gap-1 mt-1">
                                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $shift['color'] }}"></span>
                                                <span>{{ $shift['template_name'] }}</span>
                                                @if($shift['is_custom'])
                                                    <span class="text-yellow-400 text-xs">(personalizado)</span>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="mt-1">
                                            {{ \Carbon\Carbon::parse($shift['start_time'])->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($shift['end_time'])->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Absences for this day -->
                    @if(isset($absencesData[$day['date']]))
                        <div class="mt-1 space-y-1">
                            @foreach($absencesData[$day['date']] as $absence)
                                <div class="text-xs px-2 py-1 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded truncate">
                                    <div class="font-semibold truncate">🏖️ {{ $absence['employee_name'] }}</div>
                                    <div class="text-xs opacity-75">{{ ucfirst($absence['type']) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Empty state hint -->
                    @if(!isset($shiftsData[$day['date']]) && !isset($absencesData[$day['date']]) && $day['isCurrentMonth'])
                        <div class="text-center py-4">
                            <span class="text-gray-400 dark:text-gray-600 text-xs">Sin turnos</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Turnos Este Mes</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ collect($shiftsData)->flatten(1)->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ausencias</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ collect($absencesData)->flatten(1)->unique('id')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Activos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $users->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cobertura</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ collect($shiftsData)->flatten(1)->count() > 0 
                            ? number_format((collect($shiftsData)->flatten(1)->count() / collect($shiftsData)->flatten(1)->count()) * 100, 0) 
                            : 0 }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

</div>
