@props(['route', 'icon', 'label', 'badge' => null])

<a href="{{ $route }}" 
   wire:navigate
   title="{{ $label }}"
   {{ $attributes->merge(['class' => 'group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 ' . (request()->routeIs($attributes->get('active', '')) ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-md' : 'text-indigo-100 dark:text-gray-300 hover:bg-indigo-700 dark:hover:bg-gray-700')]) }}>
    
    <div class="flex items-center justify-center w-6 h-6 flex-shrink-0">
        {!! $icon !!}
    </div>
    
    <span x-show="sidebarOpen" 
          x-transition
          class="ml-3 font-medium text-sm overflow-hidden whitespace-nowrap">
        {{ $label }}
    </span>
    
    @if($badge)
        <span x-show="sidebarOpen" 
              x-transition
              class="ml-auto px-2 py-0.5 text-xs font-semibold rounded-full bg-red-500 text-white">
            {{ $badge }}
        </span>
    @endif
</a>
