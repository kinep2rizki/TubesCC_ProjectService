@php
    $user = Auth::user();
    $displayRole = 'User';
    $bgColorHex = '4d8eff'; // Default primary blue
    $badgeClasses = 'bg-primary/20 text-primary border-primary/30';
    
    if ($user) {
        if ($user->hasRole('Super Admin')) {
            $displayRole = 'Super Admin';
            $bgColorHex = 'ef4444'; // red-500
            $badgeClasses = 'bg-error/20 text-error border-error/30';
        } else {
            // Priority 2: Local community role
            if (isset($activeCommunity)) {
                $membership = $user->communityMemberships->where('community_id', $activeCommunity->id)->first();
                if ($membership) {
                    $localRole = ucfirst($membership->role);
                    $displayRole = $localRole . ' (' . $activeCommunity->name . ')';
                    if ($localRole === 'Owner') {
                        $bgColorHex = 'ec4899'; // pink-500
                        $badgeClasses = 'bg-pink-500/20 text-pink-500 border-pink-500/30';
                    } elseif ($localRole === 'Admin') {
                        $bgColorHex = 'f97316'; // orange-500
                        $badgeClasses = 'bg-orange-500/20 text-orange-500 border-orange-500/30';
                    } elseif ($localRole === 'Moderator') {
                        $bgColorHex = 'f59e0b'; // amber-500
                        $badgeClasses = 'bg-amber-500/20 text-amber-500 border-amber-500/30';
                    } elseif ($localRole === 'Member') {
                        $bgColorHex = '14b8a6'; // teal-500
                        $badgeClasses = 'bg-teal-500/20 text-teal-500 border-teal-500/30';
                    }
                }
            }
            
            // Priority 3: Other Global roles
            if ($displayRole === 'User') {
                // Global roles other than Super Admin have been removed.
                // Fallback is just User.
            }
        }
    }
@endphp
<header class="w-full sticky top-0 z-20 bg-background/70 backdrop-blur-xl border-b border-outline-variant/30 shadow-sm flex justify-between items-center px-lg py-sm">
    <!-- Left Side: Mobile Menu Toggle & Search -->
    <div class="flex items-center gap-md">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-on-surface hover:bg-surface-variant/50 transition-colors p-sm rounded cursor-pointer active:scale-95 transition-transform">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="relative hidden sm:block">
            <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[20px]">search</span>
            <input class="bg-surface-container border border-outline-variant/50 rounded-lg pl-xl pr-md py-xs text-sm font-body-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder:text-on-surface-variant w-64 transition-all bg-opacity-50" placeholder="Search events, participants..." type="text"/>
        </div>
    </div>
    <!-- Right Side: Actions & Secondary -->
    <div class="flex items-center gap-sm">
        <div x-data="{ openCommunityMenu: false }" class="relative mr-sm">
            <button @click="openCommunityMenu = !openCommunityMenu" @click.outside="openCommunityMenu = false" class="flex items-center gap-xs bg-surface-container border border-outline-variant/50 px-sm py-xs rounded hover:bg-surface-variant/50 transition-colors cursor-pointer active:scale-95 transition-transform">
                <div class="w-5 h-5 rounded bg-primary/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[14px]">groups</span>
                </div>
                <span class="font-label-caps text-label-caps text-on-surface ml-1">{{ $activeCommunity->name ?? 'No Community' }}</span>
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform" :class="openCommunityMenu ? 'rotate-180' : ''">expand_more</span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="openCommunityMenu" x-transition.origin.top.right style="display: none;" class="absolute top-full right-0 mt-2 w-64 bg-surface-container-high border border-outline-variant/30 rounded-lg shadow-xl overflow-hidden py-2 z-50">
                <div class="px-md py-xs">
                    <p class="text-[10px] font-label-caps text-on-surface-variant mb-2">YOUR COMMUNITIES</p>
                </div>
                
                @foreach(Auth::user()->communityMemberships()->with('community')->get() as $membership)
                    @php $community = $membership->community; @endphp
                    @if(isset($activeCommunity) && $activeCommunity->id == $community->id)
                        <!-- Active Community -->
                        <a href="{{ route('communities.switch', $community->id) }}" class="flex items-center justify-between px-md py-sm bg-primary/10 hover:bg-primary/20 transition-colors group">
                            <div class="flex items-center gap-md">
                                <div class="w-8 h-8 rounded bg-primary flex items-center justify-center text-on-primary shadow-sm flex-shrink-0">
                                    <span class="font-bold text-xs">{{ strtoupper(substr($community->name, 0, 2)) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-body-sm text-body-sm text-on-surface font-bold leading-tight">{{ $community->name }}</span>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-primary text-[18px]">check</span>
                        </a>
                    @else
                        <!-- Inactive Community -->
                        <a href="{{ route('communities.switch', $community->id) }}" class="flex items-center gap-md px-md py-sm hover:bg-white/5 transition-colors group">
                            <div class="w-8 h-8 rounded bg-surface-variant flex items-center justify-center text-on-surface shadow-sm flex-shrink-0">
                                <span class="font-bold text-xs">{{ strtoupper(substr($community->name, 0, 2)) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-sm text-body-sm text-on-surface font-semibold leading-tight group-hover:text-primary transition-colors">{{ $community->name }}</span>
                            </div>
                        </a>
                    @endif
                @endforeach

                <div class="h-px w-full bg-outline-variant/30 my-2"></div>
                
                <!-- Create New Community Action -->
                <button class="w-full flex items-center gap-md px-md py-sm hover:bg-white/5 text-on-surface transition-colors group text-left">
                    <div class="w-8 h-8 rounded border border-dashed border-outline-variant group-hover:border-primary flex items-center justify-center text-on-surface-variant group-hover:text-primary transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </div>
                    <span class="font-body-sm text-body-sm font-semibold group-hover:text-primary transition-colors">Create New Community</span>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-xs border-l border-outline-variant/30 pl-sm">
            <div x-data="{ openNotifications: false }" class="relative">
                <button @click="openNotifications = !openNotifications" @click.outside="openNotifications = false" :class="openNotifications ? 'bg-surface-variant/50 text-on-surface' : 'text-on-surface-variant hover:bg-surface-variant/50'" class="transition-colors p-sm rounded cursor-pointer active:scale-95 transition-transform relative">
                    <span class="material-symbols-outlined" :class="openNotifications ? 'fill' : ''" :style="openNotifications ? 'font-variation-settings:\'FILL\' 1' : ''">notifications</span>
                    <span class="absolute top-[6px] right-[6px] w-2 h-2 bg-primary rounded-full"></span>
                </button>

                <!-- Popup Panel -->
                <div x-show="openNotifications" x-transition.origin.top.right style="display: none;" class="absolute top-full right-0 mt-2 w-80 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-2xl z-50 overflow-hidden text-left">
                    <div class="px-md py-sm border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-highest/50">
                        <h3 class="font-body-base font-bold text-on-surface">Notifications</h3>
                        <button class="text-[10px] font-label-caps text-primary hover:underline">Mark all read</button>
                    </div>
                    <div class="max-h-80 overflow-y-auto custom-scrollbar">
                        @forelse(Auth::user()->notifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-md border-b border-outline-variant/10 hover:bg-surface-variant/50 transition-colors cursor-pointer relative group">
                                <div class="flex gap-sm items-start">
                                    @if(empty($notification->read_at))
                                        <div class="w-2 h-2 rounded-full bg-primary mt-1.5 absolute left-2"></div>
                                    @endif
                                    <div class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center flex-shrink-0 text-emerald-400 border border-emerald-500/20 ml-2">
                                        <span class="material-symbols-outlined text-[16px]">workspace_premium</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-on-surface font-body-sm leading-relaxed">{!! $notification->data['message'] ?? 'You have a new notification.' !!}</p>
                                        <p class="text-[10px] text-on-surface-variant font-mono-code mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-md text-center">
                                <p class="text-xs text-on-surface-variant">No notifications.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-xs text-center border-t border-outline-variant/30 bg-surface-container-highest/20">
                        <a href="#" class="text-[10px] font-label-caps text-on-surface-variant hover:text-primary transition-colors py-1 block">View All Notifications</a>
                    </div>
                </div>
            </div>
            <button class="text-on-surface-variant hover:bg-surface-variant/50 transition-colors p-sm rounded cursor-pointer active:scale-95 transition-transform hidden sm:block">
                <span class="material-symbols-outlined">apps</span>
            </button>
            <div x-data="{ openAccountMenu: false }" class="relative ml-xs">
                <button @click="openAccountMenu = !openAccountMenu" @click.outside="openAccountMenu = false" class="rounded-full overflow-hidden border border-outline-variant/50 hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background flex items-center justify-center">
                    <img alt="User profile" class="w-8 h-8 object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background={{ $bgColorHex }}&color=fff"/>
                </button>

                <!-- Pop-up Menu -->
                <div x-show="openAccountMenu" x-transition.origin.top.right style="display: none;" class="absolute top-full right-0 mt-2 w-56 bg-surface-container-high border border-outline-variant/30 rounded-lg shadow-xl overflow-hidden py-1 z-50">
                    <div class="px-md py-sm border-b border-outline-variant/30 mb-1">
                        <p class="font-body-sm text-body-sm text-on-surface font-semibold leading-tight">{{ Auth::user()->name ?? 'Guest User' }}</p>
                        <p class="text-[10px] text-on-surface-variant font-mono-code leading-tight mt-1">{{ Auth::user()->email ?? 'guest@peta.com' }}</p>
                        @auth
                            <div class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeClasses }}">
                                {{ $displayRole }}
                            </div>
                        @endauth
                    </div>
                    <a href="{{ route('settings') }}" class="flex items-center gap-3 px-md py-sm hover:bg-white/5 transition-colors text-on-surface">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        <span class="font-body-sm text-body-sm">My Profile</span>
                    </a>
                    <div class="h-px w-full bg-outline-variant/30 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-md py-sm hover:bg-error/10 text-error transition-colors group text-left">
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">logout</span>
                            <span class="font-body-sm text-body-sm font-semibold">Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
