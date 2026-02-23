<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Lexiscope') }} — Interactive Constitution</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|playfair-display:400,600,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        .font-sans {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar for sidebar */
        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgb(100 116 139 / 0.3);
            border-radius: 99px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: rgb(100 116 139 / 0.6);
        }
    </style>
</head>

<body
    class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 flex h-screen overflow-hidden">
    {{ $slot }}
</body>

</html>
