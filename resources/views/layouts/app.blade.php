<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kopi Senja' }} — Modern Cafe POS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#F9F6F0] text-coffee-950 antialiased selection:bg-caramel-500 selection:text-white" x-data="{
    toast: { show: false, message: '', type: 'success' },
    showToast(msg, type = 'success') {
        this.toast.message = msg;
        this.toast.type = type;
        this.toast.show = true;
        setTimeout(() => { this.toast.show = false; }, 3500);
    }
}">

    <!-- Flash Message listener -->
    @if(session('success'))
        <div x-init="showToast('{{ session('success') }}', 'success')"></div>
    @endif
    @if(session('error'))
        <div x-init="showToast('{{ session('error') }}', 'error')"></div>
    @endif

    <div class="flex h-screen overflow-hidden">
        <!-- Persistent Sidebar -->
        @include('components.sidebar')

        <!-- Main Viewport Area -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-[#FAF7F2]">
            {{ $slot }}
        </main>
    </div>

    <!-- Global Toast Notification -->
    <div 
        x-show="toast.show" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
        x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold border backdrop-blur-md"
        :class="{
            'bg-coffee-900 text-cream-50 border-coffee-700/50': toast.type === 'success',
            'bg-red-900 text-red-50 border-red-700/50': toast.type === 'error'
        }"
        style="display: none;"
    >
        <template x-if="toast.type === 'success'">
            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-sage-500 text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
        </template>
        <template x-if="toast.type === 'error'">
            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </span>
        </template>
        <span x-text="toast.message"></span>
    </div>

</body>
</html>
