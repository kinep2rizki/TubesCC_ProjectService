@props([
    'id',
    'participantId',
    'eventId',
    'name',
    'role',
    'email',
    'institution',
    'status',
    'date',
    'avatarUrl' => null,
    'avatarInitials' => null,
    'roleColor' => 'text-on-surface-variant opacity-70'
])

@php
    $statusColors = [
        'Registered' => [
            'bg' => 'bg-tertiary-container/20',
            'border' => 'border-tertiary-container/30',
            'text' => 'text-tertiary',
            'dot' => 'bg-tertiary'
        ],
        'Attended' => [
            'bg' => 'bg-primary-container/20',
            'border' => 'border-primary-container/30',
            'text' => 'text-primary',
            'dot' => 'bg-primary'
        ],
        'Not Attending' => [
            'bg' => 'bg-outline-variant/20',
            'border' => 'border-outline-variant/50',
            'text' => 'text-on-surface-variant',
            'dot' => 'bg-outline-variant'
        ]
    ];
    
    // Fallback to Registered if status not recognized
    $colors = $statusColors[$status] ?? $statusColors['Registered'];
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
                <span class="font-label-caps text-label-caps {{ $roleColor }}">{{ $role }}</span>
            </div>
        </div>
    </td>
    <td class="px-md py-3 font-body-sm text-body-sm text-on-surface-variant">{{ $email }}</td>
    <td class="px-md py-3 font-body-sm text-body-sm text-on-surface-variant">{{ $institution }}</td>
    <td class="px-md py-3 relative" x-data="{ openStatus: false }">
        <button @click="openStatus = !openStatus" @click.outside="openStatus = false" class="inline-flex items-center px-2 py-0.5 rounded-full {{ $colors['bg'] }} border {{ $colors['border'] }} {{ $colors['text'] }} font-label-caps text-[10px] gap-1 focus:outline-none hover:opacity-80 transition-opacity cursor-pointer">
            <span class="w-1.5 h-1.5 rounded-full {{ $colors['dot'] }}"></span>
            {{ $status }}
            <span class="material-symbols-outlined text-[12px] ml-1">expand_more</span>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="openStatus" 
             x-transition.opacity.duration.200ms
             class="absolute left-md top-10 w-36 bg-surface-container-high border border-outline-variant/30 rounded-lg shadow-lg z-50 py-xs"
             style="display: none;">
            <form method="POST" action="{{ route('participants.update', ['eventId' => $eventId, 'participantId' => $participantId]) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Registered">
                <button type="submit" class="w-full text-left px-sm py-2 hover:bg-white/5 text-[11px] font-label-caps text-tertiary flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span> Registered
                </button>
            </form>

            <form method="POST" action="{{ route('participants.update', ['eventId' => $eventId, 'participantId' => $participantId]) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Attended">
                <button type="submit" class="w-full text-left px-sm py-2 hover:bg-white/5 text-[11px] font-label-caps text-primary flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> Attended
                </button>
            </form>

            <form method="POST" action="{{ route('participants.update', ['eventId' => $eventId, 'participantId' => $participantId]) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Not Attending">
                <button type="submit" class="w-full text-left px-sm py-2 hover:bg-white/5 text-[11px] font-label-caps text-error flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Not Attending
                </button>
            </form>
        </div>
    </td>
    <td class="px-md py-3 font-mono-code text-mono-code text-on-surface-variant hidden md:table-cell">{{ $date }}</td>
    <td class="px-md py-3 text-right">
        <button class="text-on-surface-variant hover:text-on-surface opacity-0 group-hover:opacity-100 transition-opacity p-1">
            <span class="material-symbols-outlined text-[20px]">more_vert</span>
        </button>
    </td>
</tr>
