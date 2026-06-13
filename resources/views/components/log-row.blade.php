@props([
    'timestamp',
    'name',
    'ticketType',
    'status', // granted, denied
    'gate'
])

<tr class="border-b border-outline-variant/10 hover:bg-white/5 transition-colors">
    <td class="py-sm px-md text-outline-variant">{{ $timestamp }}</td>
    <td class="py-sm px-md font-body-sm text-body-sm {{ $status === 'denied' ? 'text-outline-variant italic' : '' }}">{{ $name }}</td>
    <td class="py-sm px-md">
        <span class="px-2 py-1 rounded {{ $status === 'denied' ? 'bg-surface-variant text-on-surface-variant' : ($ticketType === 'VIP' ? 'bg-secondary-container/20 text-primary' : 'bg-surface-variant text-on-surface-variant') }} text-xs">
            {{ $ticketType }}
        </span>
    </td>
    <td class="py-sm px-md">
        @if($status === 'granted')
            <span class="text-primary flex items-center gap-xs">
                <span class="material-symbols-outlined text-[16px]">check</span> Granted
            </span>
        @else
            <span class="text-error flex items-center gap-xs">
                <span class="material-symbols-outlined text-[16px]">close</span> Denied
            </span>
        @endif
    </td>
    <td class="py-sm px-md text-right text-outline-variant">{{ $gate }}</td>
</tr>
