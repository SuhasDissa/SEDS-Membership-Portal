<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SEDS UoM - Member Registration' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-effect {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(31, 41, 55, 0.8);
            border: 1px solid rgba(75, 85, 99, 0.3);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
        }

        .progress-bar {
            background: linear-gradient(90deg, #3B82F6 0%, #6366F1 100%);
        }

        .section-card {
            transition: all 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-2px);
        }

        html {
            scroll-behavior: smooth;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
        }
    </style>
</head>

<body class="min-h-screen gradient-bg text-gray-900">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60"
            viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg
            fill="%236B7280" fill-opacity="0.05"%3E%3Cpath
            d="m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"
            /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Header -->
    <x-mary-nav sticky class="border-gray-200 bg-white/90 backdrop-blur-sm shadow-sm z-50">
        <x-slot:brand>
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <x-app-logo />
                </a>
            </div>
        </x-slot:brand>

        <x-slot:actions>
            <x-mary-button link="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'btn-ghost text-gray-900 font-semibold' : 'btn-ghost text-gray-600 hover:text-gray-900' }}"
                aria-current="{{ request()->routeIs('home') ? 'page' : '' }}">
                Home
            </x-mary-button>

            <x-mary-button link="{{ route('member.register') }}"
                class="{{ request()->routeIs('member.register') ? 'btn-ghost text-gray-900 font-semibold' : 'btn-ghost text-gray-600 hover:text-gray-900' }}"
                aria-current="{{ request()->routeIs('member.register') ? 'page' : '' }}">
                Registration
            </x-mary-button>

            <x-mary-button link="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'btn-ghost text-gray-900 font-semibold' : 'btn-ghost text-gray-600 hover:text-gray-900' }}"
                aria-current="{{ request()->routeIs('dashboard') ? 'page' : '' }}">
                Admin dashboard
            </x-mary-button>
        </x-slot:actions>
    </x-mary-nav>

    <!-- Main Content -->
    <main class="relative z-10 flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white/90 backdrop-blur-sm py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} Space Exploration and Development Society - University of Moratuwa
                </p>
                <x-mary-badge value="Powered by Laravel & Livewire" class="badge-ghost" />
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
