<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SEDS Mora - Students for Exploration and Development of Space at University of Moratuwa. Join our community of space enthusiasts.">
    <meta name="theme-color" content="#1a1a2e">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    {{-- Favicon - Using inline SVG for now --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'><path fill='%234169E1' d='m 89.510153,40.52223 -1.539515,7.97749 -3.638856,-1.959384 c -0.127193,1.244365 1.438615,2.538026 1.959384,3.638855 -2.000403,0.174441 -5.208508,0.673444 -6.857842,1.819428 1.840236,1.109559 4.781464,1.095747 6.857842,1.679471 l -2.379252,3.918768 4.338636,-1.819427 c 0.0069,2.498492 0.512192,5.059906 0.687257,7.557622 0.180437,2.574381 0.147584,5.437012 1.13217,7.837534 h 0.139957 c 0.01388,-5.031129 0.768741,-10.263802 1.399559,-15.255201 l 4.618547,1.679472 -2.659163,-3.918768 6.997803,-1.539515 V 51.998619 C 98.565088,50.830571 95.683088,50.57316 93.42892,50.039236 l 2.09934,-3.358943 -4.058723,1.679471 c -0.08379,-1.951841 -0.598717,-3.951518 -0.92181,-5.87815 -0.135668,-0.80901 -0.07139,-1.801661 -1.037574,-1.959384'/></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- AOS Library --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">
    
    {{ $slot }}

    {{--  TOAST area --}}
    <x-toast />
    
    {{-- AOS Initialization --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
        });
    </script>
</body>
</html>
