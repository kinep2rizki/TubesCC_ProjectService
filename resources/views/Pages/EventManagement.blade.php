@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="max-w-container-max mx-auto space-y-xl pb-32 md:pb-2xl w-full">
    <!-- Page Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
        <div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Events</h2>
            <p class="font-body-base text-body-base text-on-surface-variant mt-xs">Manage upcoming tech events and hackathons.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-sm items-start sm:items-center">
            <!-- Mobile Search (if hidden on desktop nav) -->
            <div class="md:hidden relative w-full sm:w-auto flex-1">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input class="w-full bg-surface-container rounded-lg py-2 pl-xl pr-sm text-body-sm font-body-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:outline-none" placeholder="Search..." type="text"/>
            </div>
            
            <button class="flex items-center gap-xs px-md py-2 rounded-lg border border-outline-variant/50 text-on-surface-variant hover:bg-surface-variant transition-colors font-label-caps text-label-caps w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            
            <!-- Primary Blue Button -->
            <button class="flex items-center gap-xs px-md py-2 rounded-lg bg-gradient-to-r from-primary-container to-blue-600 text-white font-label-caps text-label-caps w-full sm:w-auto justify-center shadow-[0_0_15px_rgba(77,142,255,0.3)] hover:shadow-[0_0_20px_rgba(77,142,255,0.5)] transition-shadow">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Create Event
            </button>
        </div>
    </div>

    <!-- Events Grid (Bento/Card Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        
        <x-event-card 
            title="Global AI Hack 2024"
            type="Hackathon"
            date="Oct 15"
            status="Upcoming"
            participantsCount="45 Participants"
            avatarsLabel="+42"
            progressLabel="Attendance Target"
            progressValue="85%"
            imageUrl="https://lh3.googleusercontent.com/aida-public/AB6AXuCCJzpsovxejyPZao4ZlZ6jp2V_ooh-F6tRVEq_s-y7ZfXOewlRmauNdg_e0b9T9CF3KapW1we9FZoNHCKfC9vLvTGpzA9UPyOV3oCtOWrfIusmzJBMfWI5I10f_Lmbr4qx0dYKDBnWnm7uVkbQoZYOJq_fGIQ-j6Y-9qXGq_wa4ErxsJ2yAdiVMLvON3KnJB2d59B9cG_puGMXs6ozBSsoZOHtjSC6c9M8173lkkl8q0PnoLpTW0AWi7SirXWAzIdDp0bPFIpOkoN0"
            hasProgressGradient="true"
        />

        <x-event-card 
            title="DevOps Summit V2"
            type="Conference"
            date="Oct 10"
            status="Live Now"
            participantsCount="1,204 Participants"
            avatarsLabel="+1k"
            progressLabel="Attendance Rate"
            progressValue="92%"
            imageUrl="https://lh3.googleusercontent.com/aida-public/AB6AXuA-9D4A79WZOj224tXp7KC5aTfw7dI0NPiEbX0q5eYc13SKmilyfrwVFF-4_DHiOTeWkjO31IHEFSoYFoWTK2Z_VzEUqAJj7A1dUHVRkgI_HAWhshFn4EKRVao2E5VItNshEqaBVFJ45ibROMWczocLDjKqZZudHH5Ry2TWGXL0FN7rk_zPpx79o2VDZUKC9YxNWXDAwp2E5BuSXPZ5Ms4H1oeegb_TYUcPjT6FLT4ADegrSuLw2beYI4luoZA6JI2sZjoBDZU5uJqD"
            overlayClass="bg-gradient-to-br from-emerald-900/40 to-teal-900/40 mix-blend-overlay"
            statusClass="text-emerald-400 border border-emerald-400/30"
            typeClass="text-emerald-400 bg-emerald-400/10"
            progressTextClass="text-emerald-400"
            progressBarClass="bg-emerald-400"
        />

        <x-event-card 
            title="React Performance Deep Dive"
            type="Workshop"
            date="Sep 28"
            status="Completed"
            participantsCount="30 Participants"
            progressLabel="Final Attendance"
            progressValue="100%"
            imageUrl="https://lh3.googleusercontent.com/aida-public/AB6AXuB18Kazuc5kY3kvZIlckKuWqoZJi7k7sS_Z8vPLHQ5DqAsA48hPQ32WgO9EPiagr1VsnyJoTwO5E7GAWgomiaO5eSIBu2MPseLUVVC_8ezwaaGPQRbPsVXrjydtbmzLiNhxn42zBXCNDEJXJnhIXDdbsuJO69D6yCo0fZOSRL5QCywFJ2Say1PVrk5mz6iHFBT7d3F-MCRD2WslCDK_cP9372aPhXwTx1WtbPTIA5YwahNkM0jhF8twxolgE8rccv97Hwd3gIAMZIPm"
            cardClass="opacity-70"
            imageContainerClass="grayscale"
            overlayClass="bg-surface-container-lowest/60"
            statusClass="text-on-surface-variant border border-outline-variant/30"
            typeClass="text-on-surface-variant bg-surface-container-high"
            titleClass="text-on-surface-variant"
            progressTextClass="text-on-surface-variant"
            progressBarClass="bg-outline-variant"
        />

    </div>
</div>
@endsection
