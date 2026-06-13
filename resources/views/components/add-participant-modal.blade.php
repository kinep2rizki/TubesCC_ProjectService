<div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

    <!-- Modal Panel -->
    <div x-show="showAddModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-xl bg-surface-container-lowest border border-outline-variant/30 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-lg z-10">
        <div class="flex justify-between items-center mb-md border-b border-outline-variant/20 pb-sm">
            <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">Add New Participant</h3>
            <button @click="showAddModal = false" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="space-y-sm">
            <div>
                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Full Name</label>
                <input type="text" class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base" placeholder="e.g. John Doe">
            </div>
            <div>
                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Email Address</label>
                <input type="email" class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base" placeholder="e.g. john@example.com">
            </div>
            <div>
                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Institution / Company</label>
                <input type="text" class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base" placeholder="e.g. Acme Corp">
            </div>
            <div class="grid grid-cols-2 gap-md">
                <div class="relative">
                    <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Role</label>
                    <select class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md pr-xl py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base appearance-none">
                        <option>Attendee</option>
                        <option>Speaker</option>
                        <option>VIP</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-sm top-8 text-on-surface-variant pointer-events-none">expand_more</span>
                </div>
                <div class="relative">
                    <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Status</label>
                    <select class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md pr-xl py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base appearance-none">
                        <option>Confirmed</option>
                        <option>Pending Payment</option>
                        <option>Waitlist</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-sm top-8 text-on-surface-variant pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>
        <div class="mt-xl flex justify-end gap-sm border-t border-outline-variant/20 pt-md">
            <button @click="showAddModal = false" type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 text-on-surface font-body-sm hover:bg-surface-variant/50 transition-colors">Cancel</button>
            <button @click="showAddModal = false" type="button" class="px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm hover:bg-primary-fixed transition-colors shadow-sm">Save Participant</button>
        </div>
    </div>
</div>
