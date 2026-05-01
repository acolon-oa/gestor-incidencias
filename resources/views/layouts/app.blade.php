<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Aplicar el tema guardado o por defecto light
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        /* Global Background consistency */
        body {
            background-color: hsl(var(--b2)); 
            min-height: 100vh;
        }

        /* Dark mode contrast fixes */
        [data-theme='dark'] {
            color-scheme: dark;
            --b1: 220 15% 12%; /* Slightly lighter base-100 for better distinction */
            --b2: 220 15% 8%;   /* Darker base-200 */
        }

        /* Accessibile text colors in dark mode (Silver-Gray instead of pure white) */
        [data-theme='dark'] .text-base-content,
        [data-theme='dark'] h1, 
        [data-theme='dark'] h2, 
        [data-theme='dark'] h3 {
            color: #d1d5db !important; /* Silver-gray (gray-300) */
        }

        [data-theme='dark'] .font-black,
        [data-theme='dark'] .font-bold {
            color: #e5e7eb !important; /* Slightly brighter for emphasis (gray-200) */
        }
        
        /* Secondary and Muted text in dark mode */
        [data-theme='dark'] .text-base-content\/40,
        [data-theme='dark'] .text-gray-400,
        [data-theme='dark'] .opacity-60,
        [data-theme='dark'] .italic {
            color: #9ca3af !important; /* Clearly visible gray (gray-400) */
        }

        /* Borders in dark mode */
        [data-theme='dark'] .border-base-content\/5,
        [data-theme='dark'] .border-base-content\/10,
        [data-theme='dark'] .border-gray-200,
        [data-theme='dark'] .border-gray-100 {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Fix for specific DaisyUI components in dark mode */
        [data-theme='dark'] .bg-base-100 { background-color: hsl(var(--b1)) !important; }
        [data-theme='dark'] .bg-base-200 { background-color: hsl(var(--b2)) !important; }
        
        /* Inputs/Textareas in dark mode - Solid background to prevent transparency issues */
        [data-theme='dark'] .input, 
        [data-theme='dark'] .select, 
        [data-theme='dark'] .textarea {
            background-color: hsl(var(--b1)) !important; /* Solid base-100 */
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #d1d5db !important;
        }

        /* Ensure options have solid backgrounds on all browsers */
        [data-theme='dark'] select option {
            background-color: hsl(var(--b1)) !important;
            color: #d1d5db !important;
        }
        [data-theme='dark'] .input:focus, 
        [data-theme='dark'] .select:focus, 
        [data-theme='dark'] .textarea:focus {
            border-color: hsl(var(--p)) !important;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: rgba(156, 163, 175, 0.3); 
            border-radius: 20px; 
        }
        [data-theme='dark'] ::-webkit-scrollbar-thumb { 
            background: rgba(255, 255, 255, 0.1); 
        }
        ::-webkit-scrollbar-thumb:hover { background: rgba(156, 163, 175, 0.5); }
    </style>
</head>

<body class="bg-base-200 text-base-content transition-colors duration-300">
    <div class="drawer drawer-open min-h-screen">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" checked />

        <!-- MAIN CONTENT -->
        <div class="drawer-content flex flex-col h-screen overflow-hidden">
            <div class="flex-1 overflow-y-auto px-6 py-6 md:px-10 space-y-4">
                


                <x-navbar />
                @yield('content')
            </div>
        </div>

        <x-sidebar />
    </div>
    @auth
        <div class="fixed bottom-8 right-10 pointer-events-none z-[9999] opacity-20 select-none">
            <span class="text-2xl font-black uppercase tracking-[0.3em] text-base-content whitespace-nowrap">
                {{ auth()->user()->department->name ?? 'N/A' }}
            </span>
        </div>
    @endauth
</body>
</html>
