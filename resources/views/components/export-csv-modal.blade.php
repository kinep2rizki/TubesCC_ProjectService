<div x-show="showExportModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="showExportModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="showExportModal = false"></div>

    <!-- Modal Panel -->
    <div x-show="showExportModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-xl bg-surface-container-lowest border border-outline-variant/30 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md p-lg z-10">
        <div class="flex justify-between items-center mb-md border-b border-outline-variant/20 pb-sm">
            <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">Export Participants</h3>
            <button @click="showExportModal = false" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="space-y-sm">
            <p class="text-body-sm text-on-surface-variant">Choose the data scope you want to export as a CSV file.</p>
            <div class="flex flex-col gap-xs mt-sm">
                <label class="flex items-center gap-2 cursor-pointer p-sm rounded hover:bg-surface-container-high transition-colors">
                    <input type="radio" name="exportScope" checked class="text-primary bg-surface-container-high border-outline-variant focus:ring-primary h-4 w-4">
                    <span class="text-body-sm text-on-surface">All Participants (128)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-sm rounded hover:bg-surface-container-high transition-colors">
                    <input type="radio" name="exportScope" class="text-primary bg-surface-container-high border-outline-variant focus:ring-primary h-4 w-4">
                    <span class="text-body-sm text-on-surface">Currently Selected (<span x-text="selected.length"></span>)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-sm rounded hover:bg-surface-container-high transition-colors">
                    <input type="radio" name="exportScope" class="text-primary bg-surface-container-high border-outline-variant focus:ring-primary h-4 w-4">
                    <span class="text-body-sm text-on-surface">Current View/Filters</span>
                </label>
            </div>
        </div>
        <div class="mt-lg flex justify-end gap-sm border-t border-outline-variant/20 pt-md">
            <button @click="showExportModal = false" type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 text-on-surface font-body-sm hover:bg-surface-variant/50 transition-colors">Cancel</button>
            <a href="{{ route('participants.export', ['eventId' => $event->id ?? 1, 'search' => request('search'), 'status' => request('status')]) }}" @click="showExportModal = false" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm hover:bg-primary-fixed transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span> Download CSV
            </a>
        </div>
    </div>
</div>
