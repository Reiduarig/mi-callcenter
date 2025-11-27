<div>
    {{-- Desktop Sidebar --}}
    <aside x-cloak 
           :class="sidebarOpen ? 'w-64' : 'w-20'"
           class="fixed left-0 top-0 z-30 h-screen bg-gradient-to-br from-indigo-800 to-indigo-900 dark:from-gray-800 dark:to-gray-900 transition-all duration-300 overflow-hidden flex flex-col shadow-xl hidden lg:flex"
        >
        
        {{-- Logo --}}
        <x-sidebar-logo />

        {{-- Navigation Menu --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 scrollbar-thin scrollbar-thumb-indigo-700 scrollbar-track-transparent">
        
            {{-- Dashboard --}}
            @can('view-dashboard')
            <x-sidebar-link 
                route="{{ route('dashboard') }}" 
                active="dashboard"
                :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\' /></svg>'"
                label="Dashboard" />
            @endcan

            {{-- Staff Section --}}
            @canany(['view-users', 'view-shifts', 'view-own-shifts', 'view-absences', 'view-own-absences', 'view-own-calendar'])
                <x-sidebar-section title="Recursos Humanos" />
            
                @can('view-users')
                <x-sidebar-link 
                    route="{{ route('staff.users.index') }}" 
                    active="staff.users.*"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\' /></svg>'"
                    label="Usuarios" />
                @endcan

                @canany(['view-shifts', 'view-own-shifts'])
                <x-sidebar-link 
                    route="{{ route('staff.shifts.index') }}" 
                    active="staff.shifts.index"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
                    label="{{ auth()->user()->hasRole('agente') ? 'Mis Turnos' : 'Turnos' }}" />
                @endcanany

                @canany(['view-calendar', 'view-own-calendar'])
                <x-sidebar-link 
                    route="{{ route('staff.shifts.calendar') }}" 
                    active="staff.shifts.calendar"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>'"
                    label="{{ auth()->user()->hasRole('agente') ? 'Mi Calendario' : 'Calendario' }}" />
                @endcanany

                @can('manage-shift-templates')
                <x-sidebar-link 
                    route="{{ route('staff.shift-templates.index') }}" 
                    active="staff.shift-templates.*"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z\' /></svg>'"
                    label="Plantillas" />
                @endcan

                @canany(['view-absences', 'view-own-absences'])
                <x-sidebar-link 
                    route="{{ route('staff.absences.index') }}" 
                    active="staff.absences.*"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\' /></svg>'"
                    label="{{ auth()->user()->hasRole('agente') ? 'Mis Ausencias' : 'Ausencias' }}" />
                @endcanany
            @endcanany

            {{-- System Section --}}
            @canany(['manage-roles', 'view-audit-logs'])
                <x-sidebar-section title="Sistema" />
                
                @can('manage-roles')
                <x-sidebar-link 
                    route="{{ route('staff.roles.index') }}" 
                    active="staff.roles.*"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z\' /></svg>'"
                    label="Roles y Permisos" />
                @endcan

                @can('view-audit-logs')
                <x-sidebar-link 
                    route="{{ route('staff.audit-logs.index') }}" 
                    active="staff.audit-logs.*"
                    :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01\' /></svg>'"
                    label="Auditoría" />
                @endcan
            @endcanany

        </nav>

        {{-- User Profile Footer --}}
        <x-sidebar-footer />

    </aside>

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="mobileMenuOpen" 
        @click="mobileMenuOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
        x-cloak>
    </div>

    {{-- Mobile Sidebar --}}
    <aside x-show="mobileMenuOpen"
        @click.away="mobileMenuOpen = false"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 z-50 h-screen w-64 bg-gradient-to-br from-indigo-800 to-indigo-900 dark:from-gray-800 dark:to-gray-900 overflow-hidden flex flex-col shadow-xl lg:hidden"
        x-cloak>
        
        {{-- Mobile Header --}}
        <div class="flex items-center justify-between p-4 border-b border-indigo-700 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white dark:bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white dark:text-gray-100">CallCenter</h1>
                    <p class="text-xs text-indigo-300 dark:text-gray-400">Gestión Profesional</p>
                </div>
            </div>
            <button @click="mobileMenuOpen = false" 
                    class="p-2 hover:bg-indigo-700 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            
            @can('view-dashboard')
            <a href="{{ route('dashboard') }}" 
            wire:navigate
            @click="mobileMenuOpen = false"
            class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>
            @endcan

            @canany(['view-users', 'view-shifts', 'view-absences'])
            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-indigo-300 dark:text-gray-400 uppercase tracking-wider mb-2">
                    Recursos Humanos
                </p>
                
                @can('view-users')
                <a href="{{ route('staff.users.index') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.users.*') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="ml-3 font-medium">Usuarios</span>
                </a>
                @endcan

                @can('view-shifts')
                <a href="{{ route('staff.shifts.index') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.shifts.*') && !request()->routeIs('staff.shifts.calendar') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3 font-medium">Turnos</span>
                </a>
                @endcan

                @can('view-calendar')
                <a href="{{ route('staff.shifts.calendar') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.shifts.calendar') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-3 font-medium">Calendario</span>
                </a>
                @endcan

                @can('manage-shift-templates')
                <a href="{{ route('staff.shift-templates.index') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.shift-templates.*') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z" />
                    </svg>
                    <span class="ml-3 font-medium">Plantillas</span>
                </a>
                @endcan

                @can('view-absences')
                <a href="{{ route('staff.absences.index') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.absences.*') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="ml-3 font-medium">Ausencias</span>
                </a>
                @endcan
            </div>
            @endcanany

            @canany(['manage-roles', 'view-audit-logs'])
            <div class="pt-4">
                <p class="px-3 text-xs font-semibold text-indigo-300 dark:text-gray-400 uppercase tracking-wider mb-2">
                    Sistema
                </p>
                
                @can('manage-roles')
                <a href="{{ route('staff.roles.index') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.roles.*') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="ml-3 font-medium">Roles y Permisos</span>
                </a>
                @endcan

                @can('view-audit-logs')
                <a href="{{ route('staff.audit-logs.index') }}" 
                wire:navigate
                @click="mobileMenuOpen = false"
                class="flex items-center px-3 py-3 rounded-lg transition-all {{ request()->routeIs('staff.audit-logs.*') ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="ml-3 font-medium">Auditoría</span>
                </a>
                @endcan
            </div>
            @endcanany

        </nav>

        {{-- Mobile User Footer --}}
        <div class="border-t border-indigo-700 dark:border-gray-700 p-4">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 bg-indigo-600 dark:bg-gray-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white dark:text-gray-100 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-indigo-300 dark:text-gray-400 truncate">
                        @if(auth()->user()->roles->isNotEmpty())
                            {{ ucfirst(auth()->user()->roles->first()->name) }}
                        @else
                            Sin rol asignado
                        @endif
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-3 py-2 bg-indigo-700 dark:bg-gray-700 hover:bg-indigo-600 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>

    </aside>
</div>
