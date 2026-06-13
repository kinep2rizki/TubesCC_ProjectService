@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-container-max mx-auto w-full">
    <!-- Page Header -->
    <header class="mb-xl">
        <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Settings</h1>
        <p class="font-body-base text-body-base text-on-surface-variant mt-sm max-w-2xl">Manage your account settings, security preferences, and API integrations.</p>
    </header>
    
    <!-- Settings Layout (Sidebar + Content) -->
    <div class="flex flex-col md:flex-row gap-2xl items-start relative">
        <!-- Secondary Sidebar for Settings Sections (Stripe-style) -->
        <nav class="w-full md:w-56 flex-shrink-0 sticky top-xl flex flex-row md:flex-col gap-xs overflow-x-auto md:overflow-visible pb-md md:pb-0 border-b md:border-b-0 border-outline-variant/30 z-10 bg-background/95 backdrop-blur-sm md:bg-transparent md:backdrop-blur-none">
            <a class="px-md py-sm text-on-surface font-body-sm font-semibold bg-surface-container-high/50 rounded-lg whitespace-nowrap transition-colors" href="#profile">Profile</a>
            <a class="px-md py-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg font-body-sm transition-colors whitespace-nowrap" href="#security">Security</a>
            <a class="px-md py-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg font-body-sm transition-colors whitespace-nowrap" href="#notifications">Notifications</a>
            <a class="px-md py-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg font-body-sm transition-colors whitespace-nowrap" href="#api">API Keys</a>
        </nav>
        
        <!-- Settings Forms Area -->
        <div class="flex-1 w-full max-w-3xl space-y-2xl pb-2xl">
            <!-- Profile Section -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="profile">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Profile</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Update your personal information and avatar.</p>
                </div>
                <div class="p-lg space-y-xl">
                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-lg">
                        <div class="relative w-20 h-20 rounded-full border border-outline-variant overflow-hidden bg-surface-container-highest flex-shrink-0">
                            <img alt="User Avatar" class="w-full h-full object-cover" data-alt="A high-quality, professional headshot of a young professional in a modern, well-lit indoor environment. The lighting is bright and clear, casting soft shadows. The overall tone is approachable and clean, fitting a sleek, modern tech platform's user profile image. Background is subtly blurred." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDc10OtlWIDhhtO4qDUkrajM2cug2WxrM_awC8K0IU6OSHycn3xd2C2makL_mv3vtY9JZ4nOy0nj79zNMLpBEdkVglXwJ7heEv1IPp16tyRn9ew-1CopH_kLb6fdk3EY6NREJ0_NlLOwyJw5RajD0zsKo1xxGJEdVS9kl-cRrAyEEfQbRry3EivVxc7-VmhBF6OVSR670oWotq5ie6FcfwKT-iPzAkJul5jKkrUAkZs2CF08qJ7Q09qV_MA1W6zAlMBOqWPMBLuCCSU"/>
                        </div>
                        <div class="flex gap-sm">
                            <button class="bg-surface-bright text-on-surface border border-outline-variant px-md py-sm rounded-lg font-label-caps text-label-caps hover:bg-surface-container-highest transition-colors">Change</button>
                            <button class="text-error px-md py-sm rounded-lg font-label-caps text-label-caps hover:bg-error-container/10 transition-colors">Remove</button>
                        </div>
                    </div>
                    <!-- Name Field -->
                    <div class="space-y-sm">
                        <label class="block font-label-caps text-label-caps text-on-surface-variant" for="fullName">Full Name</label>
                        <input class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all placeholder-on-surface-variant/50" id="fullName" type="text" value="Alex Mercer"/>
                    </div>
                    <!-- Email Field -->
                    <div class="space-y-sm">
                        <label class="block font-label-caps text-label-caps text-on-surface-variant" for="emailAddr">Email Address</label>
                        <input class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all placeholder-on-surface-variant/50" id="emailAddr" type="email" value="alex.mercer@example.com"/>
                    </div>
                    <!-- Bio Field -->
                    <div class="space-y-sm">
                        <label class="block font-label-caps text-label-caps text-on-surface-variant" for="bioText">Bio</label>
                        <textarea class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all placeholder-on-surface-variant/50 resize-y" id="bioText" rows="3">Senior Platform Engineer specializing in high-throughput event systems.</textarea>
                        <p class="font-body-sm text-body-sm text-on-surface-variant/70 text-right">0 / 160</p>
                    </div>
                </div>
                <div class="px-lg py-md border-t border-outline-variant/30 bg-surface-container-lowest/50 flex justify-end">
                    <button class="bg-primary-container text-on-primary-container px-lg py-sm rounded-lg font-label-caps text-label-caps hover:bg-primary-container/90 transition-colors shadow-sm">Save Changes</button>
                </div>
            </section>
            
            <!-- Security Section -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="security">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Security</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Manage your password and secure your account.</p>
                </div>
                <div class="p-lg space-y-xl">
                    <!-- Password Change -->
                    <div class="space-y-md">
                        <h3 class="font-body-base font-semibold text-on-surface">Change Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <div class="space-y-sm">
                                <label class="block font-label-caps text-label-caps text-on-surface-variant" for="currentPass">Current Password</label>
                                <input class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all" id="currentPass" placeholder="••••••••" type="password"/>
                            </div>
                            <div class="space-y-sm">
                                <label class="block font-label-caps text-label-caps text-on-surface-variant" for="newPass">New Password</label>
                                <input class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all" id="newPass" placeholder="••••••••" type="password"/>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button class="bg-surface-bright text-on-surface border border-outline-variant px-md py-sm rounded-lg font-label-caps text-label-caps hover:bg-surface-container-highest transition-colors">Update Password</button>
                        </div>
                    </div>
                    <hr class="border-outline-variant/30"/>
                    <!-- 2FA Toggle -->
                    <div class="flex items-start justify-between gap-lg">
                        <div>
                            <h3 class="font-body-base font-semibold text-on-surface">Two-Factor Authentication</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Add an extra layer of security to your account by requiring a verification code upon login.</p>
                        </div>
                        <!-- Toggle Switch UI -->
                        <button aria-checked="true" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-container focus:ring-offset-2 focus:ring-offset-background bg-primary-container" role="switch" type="button">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 translate-x-5 transform rounded-full bg-on-primary-container shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                </div>
            </section>
            
            <!-- Notifications Placeholder -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="notifications">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Notifications</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Configure how you receive alerts and updates.</p>
                </div>
                <div class="p-lg">
                    <p class="text-on-surface-variant text-body-sm">Notification settings coming soon.</p>
                </div>
            </section>

            <!-- API Section -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="api">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50 flex justify-between items-center">
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">API Keys</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Manage your development and production API keys.</p>
                    </div>
                    <button class="bg-primary-container text-on-primary-container px-md py-sm rounded-lg font-label-caps text-label-caps hover:bg-primary-container/90 transition-colors shadow-sm flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[16px]">add</span> Create Key
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant/30 bg-surface-container-low/30">
                                <th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant font-medium">Name</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant font-medium">Key</th>
                                <th class="px-lg py-sm font-label-caps text-label-caps text-on-surface-variant font-medium text-right">Created</th>
                                <th class="px-lg py-sm w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="font-mono-code text-mono-code text-on-surface divide-y divide-outline-variant/30">
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-lg py-md font-body-sm text-on-surface">Production Main</td>
                                <td class="px-lg py-md">
                                    <div class="flex items-center gap-sm">
                                        <span class="text-on-surface-variant truncate w-48">sk_live_9f8d...a1b2</span>
                                        <button class="text-on-surface-variant hover:text-primary-container opacity-0 group-hover:opacity-100 transition-opacity" title="Copy to clipboard">
                                            <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-lg py-md font-body-sm text-on-surface-variant text-right">Oct 12, 2023</td>
                                <td class="px-lg py-md text-right">
                                    <button class="text-on-surface-variant hover:text-error transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-lg py-md font-body-sm text-on-surface">Development Sandbox</td>
                                <td class="px-lg py-md">
                                    <div class="flex items-center gap-sm">
                                        <span class="text-on-surface-variant truncate w-48">sk_test_4c3b...e9f0</span>
                                        <button class="text-on-surface-variant hover:text-primary-container opacity-0 group-hover:opacity-100 transition-opacity" title="Copy to clipboard">
                                            <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-lg py-md font-body-sm text-on-surface-variant text-right">Jan 05, 2024</td>
                                <td class="px-lg py-md text-right">
                                    <button class="text-on-surface-variant hover:text-error transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple script to handle sub-nav highlighting based on scroll position
    // This adds that extra "Stripe-style" polish
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('nav[class*="sticky"] a');
        const mainScrollArea = document.querySelector('main');

        function highlightNav() {
            if (!mainScrollArea) return;
            
            let scrollY = mainScrollArea.scrollTop;
            
            sections.forEach(current => {
                const sectionHeight = current.offsetHeight;
                const sectionTop = current.offsetTop - 100; // Offset for sticky header/padding
                const sectionId = current.getAttribute('id');
                
                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navLinks.forEach(link => {
                        link.classList.remove('bg-surface-container-high/50', 'text-on-surface', 'font-semibold');
                        link.classList.add('text-on-surface-variant', 'hover:bg-surface-container-low');
                        if(link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('bg-surface-container-high/50', 'text-on-surface', 'font-semibold');
                            link.classList.remove('text-on-surface-variant', 'hover:bg-surface-container-low');
                        }
                    });
                }
            });
        }

        if (mainScrollArea) {
            mainScrollArea.addEventListener('scroll', highlightNav);
            // Trigger once on load
            highlightNav();
        }
    });
</script>
@endpush
