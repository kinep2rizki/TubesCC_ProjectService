@props([
    'id',
    'name',
    'role',
    'email',
    'institution',
    'status',
    'date',
    'avatarUrl' => null,
    'avatarInitials' => null
])

@php
    $statusColors = [
        'Confirmed' => [
            'bg' => 'bg-tertiary-container/20',
            'border' => 'border-tertiary-container/30',
            'text' => 'text-tertiary',
            'dot' => 'bg-tertiary'
        ],
        'Pending Payment' => [
            'bg' => 'bg-error-container/20',
            'border' => 'border-error-container/30',
            'text' => 'text-error',
            'dot' => 'bg-error'
        ],
        'Checked In' => [
            'bg' => 'bg-primary-container/20',
            'border' => 'border-primary-container/30',
            'text' => 'text-primary',
            'dot' => 'bg-primary'
        ],
        'Cancelled' => [
            'bg' => 'bg-outline-variant/20',
            'border' => 'border-outline-variant/50',
            'text' => 'text-on-surface-variant',
            'dot' => 'bg-outline-variant'
        ]
    ];
    
    $colors = $statusColors[$status] ?? $statusColors['Confirmed'];
@endphp

<tr class="hover:bg-white/[0.02] transition-colors group">
    <td class="px-md py-3 text-center">
        <input x-model="selected" value="{{ $id }}" class="rounded border-outline-variant bg-surface-container focus:ring-primary text-primary w-4 h-4 cursor-pointer" type="checkbox"/>
    </td>
    <td class="px-md py-3">
        <div class="flex items-center gap-sm">
            @if($avatarUrl)
                <div class="w-8 h-8 rounded-full bg-surface-container-highest border border-outline-variant/30 overflow-hidden shrink-0">
                    <img alt="Participant" class="w-full h-full object-cover" src="{{ $avatarUrl }}"/>
                </div>
            @else
                <div class="w-8 h-8 rounded-full bg-primary-container/20 border border-primary-container/30 flex items-center justify-center shrink-0 text-primary font-body-sm font-bold">
                    {{ $avatarInitials }}
                </div>
            @endif
            <div class="flex flex-col">
                <span class="font-body-sm text-body-sm font-medium text-on-surface group-hover:text-primary transition-colors">{{ $name }}</span>
                <span class="font-label-caps text-label-caps text-on-surface-variant opacity-70">{{ $role }}</span>
            </div>
        </div>
    </td>
    <td class="px-md py-3 font-body-sm text-body-sm text-on-surface-variant">{{ $email }}</td>
    <td class="px-md py-3 font-body-sm text-body-sm text-on-surface-variant">{{ $institution }}</td>
    <td class="px-md py-3">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $colors['bg'] }} border {{ $colors['border'] }} {{ $colors['text'] }} font-label-caps text-[10px] gap-1">
            <span class="w-1.5 h-1.5 rounded-full {{ $colors['dot'] }}"></span>
            {{ $status }}
        </span>
    </td>
    <td class="px-md py-3 font-mono-code text-mono-code text-on-surface-variant hidden md:table-cell">{{ $date }}</td>
    <td class="px-md py-3 text-right">
        <button class="text-on-surface-variant hover:text-on-surface opacity-0 group-hover:opacity-100 transition-opacity p-1">
            <span class="material-symbols-outlined text-[20px]">more_vert</span>
        </button>
    </td>
</tr>
