@props([
    'type' => 'default', // success, warning, default
    'initials' => null,
    'name',
    'description',
    'nameColor' => null
])

@php
    $typeClasses = [
        'success' => [
            'border' => 'border-primary/20',
            'text' => 'text-primary',
            'icon' => 'check_circle',
            'initials_bg' => 'bg-primary/10'
        ],
        'warning' => [
            'border' => 'border-error/20',
            'text' => 'text-error',
            'icon' => 'warning',
            'initials_bg' => 'bg-error/10'
        ],
        'default' => [
            'border' => 'border-transparent',
            'text' => 'text-on-surface',
            'icon' => null,
            'initials_bg' => 'bg-surface-variant'
        ]
    ];
    
    $styles = $typeClasses[$type] ?? $typeClasses['default'];
@endphp

<div class="bg-surface/50 border border-outline-variant/20 rounded-lg p-sm flex items-center gap-md hover:bg-surface-variant/30 transition-colors">
    <div class="w-10 h-10 rounded-full {{ $styles['initials_bg'] }} flex items-center justify-center shrink-0 border {{ $styles['border'] }}">
        @if($styles['icon'])
            <span class="material-symbols-outlined {{ $styles['text'] }} text-[20px]">{{ $styles['icon'] }}</span>
        @else
            <span class="font-label-caps text-label-caps {{ $styles['text'] }}">{{ $initials }}</span>
        @endif
    </div>
    <div class="flex-1 min-w-0">
        <div class="font-body-base text-body-base {{ $type === 'warning' ? 'text-outline-variant italic' : ($nameColor ?? 'text-on-surface') }} truncate">{{ $name }}</div>
        <div class="font-body-sm text-body-sm {{ $type === 'warning' ? 'text-error' : 'text-on-surface-variant' }} truncate">{{ $description }}</div>
    </div>
</div>
