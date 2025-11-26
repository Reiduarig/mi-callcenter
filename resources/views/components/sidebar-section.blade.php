@props(['title'])

<div class="pt-6 pb-2">
    <p x-show="sidebarOpen" 
       x-transition
       class="px-3 text-xs font-semibold text-indigo-300 dark:text-gray-400 uppercase tracking-wider">
        {{ $title }}
    </p>
    <div x-show="!sidebarOpen" class="h-px bg-indigo-700 dark:bg-gray-700 mx-3"></div>
</div>
