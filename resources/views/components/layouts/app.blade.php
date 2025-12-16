<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>{{ config('app.name') }} | {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @livewireStyles

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-700 bg-gray-50 pb-20 md:pb-0">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->


        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">

            <!-- Header -->
            @include('partials.header')

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto space-y-6">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer (always at bottom) -->
            <footer class="py-6 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </footer>

        </main>

    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-gray-900/50 z-30 md:hidden"></div>



    <!-- Scripts -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    @livewireScripts
    @stack('scripts')
</body>

</html>