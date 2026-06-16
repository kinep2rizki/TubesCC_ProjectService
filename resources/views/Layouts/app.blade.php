<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PETA Dashboard Overview')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body-base text-body-base bg-background text-on-background flex h-screen overflow-hidden selection:bg-primary-container selection:text-on-primary-container" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content Canvas Wrapper -->
    <div class="flex-1 flex flex-col w-full md:ml-64">
        
        <!-- Top Navbar -->
        <x-topbar />

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-md lg:p-xl flex flex-col gap-lg">
            @yield('content')
            
            <!-- Footer subtle note -->
            <div class="text-center text-xs text-outline font-mono-code mt-lg pb-lg">
                PETA Dashboard Component • Rendered v2.4.1 • All Systems Nominal
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
