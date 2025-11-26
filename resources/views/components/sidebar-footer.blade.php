<div class="border-t border-indigo-700 dark:border-gray-700 p-4 space-y-3">
    <!-- User Info -->
    <div class="flex items-center space-x-3">
        <!-- Toggle Sidebar -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="p-2 bg-indigo-700 dark:bg-gray-700 hover:bg-indigo-600 dark:hover:bg-gray-600 rounded-lg transition-colors hidden lg:block"
        >
            <svg x-show="sidebarOpen" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="!sidebarOpen" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
        <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0">
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
</div>
