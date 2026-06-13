<div x-show="showRoleModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="showRoleModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="showRoleModal = false"></div>

    <!-- Modal Panel -->
    <div x-show="showRoleModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-xl bg-surface-container-lowest border border-outline-variant/30 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl flex flex-col max-h-[90vh] z-10">
        
        <!-- Modal Header -->
        <div class="px-lg py-md border-b border-outline-variant/20 flex justify-between items-center bg-surface-container/50">
            <div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">Role & Permissions Builder</h3>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Create a custom role and assign granular permissions.</p>
            </div>
            <button @click="showRoleModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-lg overflow-y-auto flex-1 space-y-xl">
            
            <!-- Role Details Section -->
            <div class="space-y-md">
                <h4 class="font-label-caps text-label-caps text-outline uppercase tracking-wider">Role Details</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Role Name</label>
                        <input type="text" class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base" placeholder="e.g. Event Mentor">
                    </div>
                    <div>
                        <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Badge Color</label>
                        <div class="flex items-center gap-sm mt-2">
                            <button class="w-8 h-8 rounded-full bg-primary ring-2 ring-primary ring-offset-2 ring-offset-surface-container-lowest transition-all"></button>
                            <button class="w-8 h-8 rounded-full bg-secondary hover:scale-110 transition-transform"></button>
                            <button class="w-8 h-8 rounded-full bg-tertiary hover:scale-110 transition-transform"></button>
                            <button class="w-8 h-8 rounded-full bg-error hover:scale-110 transition-transform"></button>
                            <button class="w-8 h-8 rounded-full bg-emerald-500 hover:scale-110 transition-transform"></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Granular Permissions Section -->
            <div class="space-y-md">
                <h4 class="font-label-caps text-label-caps text-outline uppercase tracking-wider flex justify-between items-center">
                    Granular Permissions
                    <button class="text-[10px] text-primary lowercase tracking-normal bg-primary/10 px-2 py-0.5 rounded-full border border-primary/20 hover:bg-primary/20 transition-colors">Select all</button>
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <!-- Permission Group 1: Member Management -->
                    <div class="bg-surface-container rounded-lg border border-outline-variant/20 p-md space-y-sm">
                        <div class="flex items-center gap-2 mb-sm text-on-surface pb-sm border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-[18px] text-primary">group</span>
                            <span class="font-body-base font-semibold">Member Management</span>
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Approve join requests</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Can accept or reject new members.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Ban/Kick Members</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Can remove users from the community.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Permission Group 2: Content Moderation -->
                    <div class="bg-surface-container rounded-lg border border-outline-variant/20 p-md space-y-sm">
                        <div class="flex items-center gap-2 mb-sm text-on-surface pb-sm border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-[18px] text-secondary">gavel</span>
                            <span class="font-body-base font-semibold">Content Moderation</span>
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" checked class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Delete Posts</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Remove inappropriate content.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" checked class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Pin Announcements</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Pin messages to the top.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Permission Group 3: Event Administration -->
                    <div class="bg-surface-container rounded-lg border border-outline-variant/20 p-md space-y-sm">
                        <div class="flex items-center gap-2 mb-sm text-on-surface pb-sm border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-[18px] text-tertiary">event_note</span>
                            <span class="font-body-base font-semibold">Event Administration</span>
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Create Events</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Schedule new community events.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Scan Check-ins</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Can use QR scanner during events.</p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Permission Group 4: Analytics -->
                    <div class="bg-surface-container rounded-lg border border-outline-variant/20 p-md space-y-sm">
                        <div class="flex items-center gap-2 mb-sm text-on-surface pb-sm border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-[18px] text-emerald-400">monitoring</span>
                            <span class="font-body-base font-semibold">Analytics & Data</span>
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">View Dashboards</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Access to community growth charts.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" class="mt-1 text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                            <div>
                                <p class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Export Member Data</p>
                                <p class="text-[11px] text-on-surface-variant leading-tight">Can download CSV of info.</p>
                            </div>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-lg py-md border-t border-outline-variant/20 flex justify-end gap-sm bg-surface-container-lowest/80">
            <button @click="showRoleModal = false" type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 text-on-surface font-body-sm hover:bg-surface-variant/50 transition-colors">Cancel</button>
            <button @click="showRoleModal = false" type="button" class="flex items-center gap-xs px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm hover:bg-primary-fixed transition-colors shadow-[0_0_15px_rgba(77,142,255,0.2)]">
                <span class="material-symbols-outlined text-[18px]">save</span> Create Role
            </button>
        </div>
    </div>
</div>
