<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CallCenter') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div x-data="{ sidebarOpen: true, mobileMenuOpen: false }" class="flex h-screen overflow-hidden">
            
            <!-- Sidebar -->
            <aside 
                x-show="sidebarOpen || mobileMenuOpen"
                @click.away="mobileMenuOpen = false"
                :class="sidebarOpen ? 'w-64' : 'w-20'"
                class="hidden lg:flex flex-col bg-gradient-to-b from-indigo-900 via-indigo-800 to-indigo-900 text-white transition-all duration-300 shadow-2xl"
                x-cloak>
                
                <!-- Logo -->
                <div class="flex items-center justify-between p-4 border-b border-indigo-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div x-show="sidebarOpen" class="transition-opacity">
                            <h1 class="text-lg font-bold">CallCenter</h1>
                            <p class="text-xs text-indigo-300">Gestión Profesional</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    
                    <!-- Dashboard -->
                    @can('view-dashboard')
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3 font-medium">Dashboard</span>
                    </a>
                    @endcan

                    <!-- Staff Section -->
                    @canany(['view-users', 'view-shifts', 'view-absences'])
                    <div class="pt-4">
                        <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-indigo-300 uppercase tracking-wider mb-2">
                            Recursos Humanos
                        </p>
                        
                        @can('view-users')
                        <a href="{{ route('staff.users.index') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.users.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Usuarios</span>
                        </a>
                        @endcan

                        @can('view-shifts')
                        <a href="{{ route('staff.shifts.index') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.shifts.*') && !request()->routeIs('staff.shifts.calendar') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Turnos</span>
                        </a>
                        @endcan

                        @can('view-calendar')
                        <a href="{{ route('staff.shifts.calendar') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.shifts.calendar') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Calendario</span>
                        </a>
                        @endcan

                        @can('manage-shift-templates')
                        <a href="{{ route('staff.shift-templates.index') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.shift-templates.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Plantillas</span>
                        </a>
                        @endcan

                        @can('view-absences')
                        <a href="{{ route('staff.absences.index') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.absences.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Ausencias</span>
                        </a>
                        @endcan
                    </div>
                    @endcanany

                    <!-- Settings Section -->
                    <div class="pt-4">
                        <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-indigo-300 uppercase tracking-wider mb-2">
                            Sistema
                        </p>
                        
                        @can('manage-roles')
                        <a href="{{ route('staff.roles.index') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.roles.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Roles y Permisos</span>
                        </a>
                        @endcan

                        <a href="{{ route('profile') }}" wire:navigate
                           class="flex items-center px-3 py-3 rounded-lg transition text-indigo-100 hover:bg-indigo-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Configuración</span>
                        </a>
                    </div>
                </nav>

                <!-- User Profile & Collapse Button -->
                <div class="border-t border-indigo-700 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div x-show="sidebarOpen" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-indigo-300 truncate">
                                    @if(auth()->user()->roles->isNotEmpty())
                                        {{ ucfirst(auth()->user()->roles->first()->name) }}
                                    @else
                                        Sin rol asignado
                                    @endif
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen">
                            @csrf
                            <button type="submit" class="text-indigo-300 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="w-full flex items-center justify-center py-2 bg-indigo-700 hover:bg-indigo-600 rounded-lg transition">
                        <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                        <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </aside>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileMenuOpen" 
                 @click="mobileMenuOpen = false"
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
                 x-cloak>
            </div>

            <!-- Mobile Sidebar -->
            <aside x-show="mobileMenuOpen"
                   x-transition:enter="transform transition ease-in-out duration-300"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transform transition ease-in-out duration-300"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full"
                   class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-indigo-900 via-indigo-800 to-indigo-900 text-white z-50 lg:hidden"
                   x-cloak>
                <!-- Same content as desktop sidebar -->
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between p-4 border-b border-indigo-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold">CallCenter</h1>
                                <p class="text-xs text-indigo-300">Gestión Profesional</p>
                            </div>
                        </div>
                        <button @click="mobileMenuOpen = false">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Navigation links (same as desktop) -->
                    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    
                        <!-- Dashboard -->
                        @can('view-dashboard')
                        <a href="{{ route('dashboard') }}" wire:navigate
                        class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Dashboard</span>
                        </a>
                        @endcan

                        <!-- Staff Section -->
                        @canany(['view-users', 'view-shifts', 'view-absences'])
                        <div class="pt-4">
                            <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-indigo-300 uppercase tracking-wider mb-2">
                                Recursos Humanos
                            </p>
                            
                            @can('view-users')
                            <a href="{{ route('staff.users.index') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.users.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Usuarios</span>
                            </a>
                            @endcan

                            @can('view-shifts')
                            <a href="{{ route('staff.shifts.index') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.shifts.*') && !request()->routeIs('staff.shifts.calendar') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Turnos</span>
                            </a>
                            @endcan

                            @can('view-calendar')
                            <a href="{{ route('staff.shifts.calendar') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.shifts.calendar') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Calendario</span>
                            </a>
                            @endcan

                            @can('manage-shift-templates')
                            <a href="{{ route('staff.shift-templates.index') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.shift-templates.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Plantillas</span>
                            </a>
                            @endcan

                            @can('view-absences')
                            <a href="{{ route('staff.absences.index') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.absences.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Ausencias</span>
                            </a>
                            @endcan
                        </div>
                        @endcanany

                        <!-- Settings Section -->
                        <div class="pt-4">
                            <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-indigo-300 uppercase tracking-wider mb-2">
                                Sistema
                            </p>
                            
                            @can('manage-roles')
                            <a href="{{ route('staff.roles.index') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition {{ request()->routeIs('staff.roles.*') ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-indigo-700' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Roles y Permisos</span>
                            </a>
                            @endcan

                            <a href="{{ route('profile') }}" wire:navigate
                            class="flex items-center px-3 py-3 rounded-lg transition text-indigo-100 hover:bg-indigo-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-show="sidebarOpen" class="ml-3 font-medium">Configuración</span>
                            </a>
                        </div>
                    </nav>

                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                
                <!-- Top Header -->
                <header class="bg-white dark:bg-gray-800 shadow-sm z-10">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <button @click="mobileMenuOpen = true" class="lg:hidden text-gray-600 dark:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400">@yield('page-subtitle', 'Bienvenido al sistema')</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Quick Actions -->
                            <button class="hidden md:flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Acción Rápida
                            </button>

                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Dark Mode Toggle -->
                            <button @click="darkMode = !darkMode" 
                                    class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900">
                    <div class="container mx-auto px-6 py-8">
                        @if(session('success'))
                            <div class="mb-6 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 rounded-r-lg shadow-md">
                                <p class="font-medium">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 rounded-r-lg shadow-md">
                                <p class="font-medium">{{ session('error') }}</p>
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Toast Notification Component -->
        <x-toast />
        <x-confirm-delete />
    </body>
</html>
