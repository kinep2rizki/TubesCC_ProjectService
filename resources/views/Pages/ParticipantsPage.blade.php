@extends('layouts.app')

@section('title', 'Participants')

@section('content')
<div x-data="{ 
        selectAll: false, 
        selected: [],
        participants: ['p1', 'p2', 'p3', 'p4'],
        showExportModal: false,
        showAddModal: false,
        toggleAll() {
            if (this.selectAll) {
                this.selected = [...this.participants];
            } else {
                this.selected = [];
            }
        }
    }" 
    x-init="$watch('selected', value => { selectAll = value.length === participants.length })"
    class="max-w-container-max mx-auto w-full flex flex-col gap-xl">
    
    <!-- Event Header -->
    <x-event-header :event="$event" activeTab="participants" />

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md mt-sm">
        <div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Participant List</h3>
        </div>
        
        @php
            $user = auth()->user();
            $canManage = $user && isset($event) && $event->community ? $user->canManageParticipants($event->community_id) : false;
        @endphp
        <div class="flex items-center gap-sm w-full sm:w-auto">
            @if($canManage)
            <button @click="showExportModal = true" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-surface-container border border-outline-variant/50 text-on-surface font-body-sm text-body-sm hover:bg-surface-container-highest transition-colors flex-1 sm:flex-none">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export CSV
            </button>
            <button @click="showAddModal = true" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm text-body-sm hover:bg-primary-fixed transition-colors shadow-[0_0_15px_rgba(77,142,255,0.2)] flex-1 sm:flex-none">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Add New
            </button>
            @endif
        </div>
    </div>

    <!-- Alpine.js Table Context -->
    <div>

        <!-- Toolbar / Filters -->
        <form method="GET" action="{{ route('participants', $event->id ?? 1) }}" class="flex flex-col sm:flex-row justify-between items-center bg-surface-container-lowest p-xs rounded-xl border border-outline-variant/30 gap-sm mb-lg">
            <!-- Table specific search -->
            <div class="relative w-full sm:max-w-xs flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-outline-variant text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-2 text-on-surface placeholder-outline-variant focus:ring-1 focus:ring-primary focus:bg-surface-container transition-all font-body-sm text-body-sm" placeholder="Search participants..." type="text"/>
            </div>
            
            <!-- Filters -->
            <div class="flex items-center gap-sm w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0 hide-scrollbar no-scrollbar">
                <select name="status" onchange="this.form.submit()" class="bg-transparent border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors font-body-sm text-body-sm rounded-md px-3 py-1.5 focus:outline-none">
                    <option value="">Status (All)</option>
                    <option value="Registered" {{ request('status') == 'Registered' ? 'selected' : '' }}>Registered</option>
                    <option value="Attended" {{ request('status') == 'Attended' ? 'selected' : '' }}>Attended</option>
                </select>
                <div class="w-px h-6 bg-outline-variant/50 mx-xs hidden sm:block"></div>
                <a href="{{ route('participants', $event->id ?? 1) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-primary hover:bg-primary-container/10 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">filter_list_off</span>
                    Clear
                </a>
            </div>
        </form>

        <!-- Data Table (SaaS Style) -->
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-outline-variant/30 bg-surface-container/30">
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant w-12">
                                <div class="flex items-center justify-center">
                                    <input x-model="selectAll" @change="toggleAll" class="rounded border-outline-variant bg-surface-container-high focus:ring-primary text-primary w-4 h-4 cursor-pointer" type="checkbox"/>
                                </div>
                            </th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Name</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Email</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Institution</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Status</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant hidden md:table-cell">Registration Date</th>
                            <th class="px-md py-sm w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($participants as $participant)
                            @php
                                $membership = $participant->user ? $participant->user->communityMemberships->where('community_id', $event->community_id)->first() : null;
                                $localRole = $membership ? ucfirst($membership->role) : null;
                                
                                $roleDisplay = 'Attendee';
                                $roleColor = 'text-on-surface-variant opacity-70';

                                if ($localRole === 'Owner') {
                                    $roleDisplay = 'Owner';
                                    $roleColor = 'text-pink-500 font-semibold';
                                } elseif ($localRole === 'Admin') {
                                    $roleDisplay = 'Admin';
                                    $roleColor = 'text-orange-500 font-semibold';
                                } elseif ($localRole === 'Moderator') {
                                    $roleDisplay = 'Moderator';
                                    $roleColor = 'text-amber-500 font-semibold';
                                } elseif ($localRole === 'Member') {
                                    $roleDisplay = 'Member';
                                    $roleColor = 'text-teal-500 font-medium';
                                } else {
                                    // Check global roles
                                    if ($participant->user && $participant->user->hasRole('Super Admin')) {
                                        $roleDisplay = 'Super Admin';
                                        $roleColor = 'text-error font-semibold';
                                    }
                                }
                            @endphp
                        <x-participant-row 
                            id="p{{ $participant->id }}"
                            name="{{ $participant->user->name ?? 'Unknown' }}"
                            role="{{ $roleDisplay }}"
                            roleColor="{{ $roleColor }}"
                            email="{{ $participant->user->email ?? 'N/A' }}"
                            institution="General Participant"
                            status="{{ $participant->status }}"
                            date="{{ $participant->created_at->format('M d, Y') }}"
                            avatarInitials="{{ substr($participant->user->name ?? 'U', 0, 2) }}"
                        />
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-md text-on-surface-variant">No participants found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="flex items-center justify-between px-md py-sm bg-surface-container/30 border-t border-outline-variant/30">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Showing <span x-text="selected.length ? selected.length + ' selected' : '{{ $participants->firstItem() ?? 0 }} to {{ $participants->lastItem() ?? 0 }} of {{ $participants->total() }} results'"></span>
                </span>
                <div class="flex items-center gap-xs">
                    <a href="{{ $participants->previousPageUrl() }}" class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors {{ $participants->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </a>
                    
                    @foreach ($participants->links()->elements[0] as $page => $url)
                        <a href="{{ $url }}" class="w-8 h-8 rounded-md {{ $page == $participants->currentPage() ? 'bg-primary-container/20 text-primary border border-primary-container/30' : 'text-on-surface-variant hover:bg-white/5 border border-transparent' }} font-body-sm text-body-sm flex items-center justify-center transition-colors">
                            {{ $page }}
                        </a>
                    @endforeach

                    <a href="{{ $participants->nextPageUrl() }}" class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors {{ !$participants->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-export-csv-modal />
    <x-add-participant-modal :event="$event" />
</div>
@endsection
