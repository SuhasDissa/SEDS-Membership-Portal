<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="SEDS Mora - Students for Exploration and Development of Space at University of Moratuwa. Join our community of space enthusiasts.">
    <meta name="theme-color" content="#1a1a2e">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    {{-- Favicon - Using inline SVG for now --}}
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'><path fill='%234169E1' d='m 89.510153,40.52223 -1.539515,7.97749 -3.638856,-1.959384 c -0.127193,1.244365 1.438615,2.538026 1.959384,3.638855 -2.000403,0.174441 -5.208508,0.673444 -6.857842,1.819428 1.840236,1.109559 4.781464,1.095747 6.857842,1.679471 l -2.379252,3.918768 4.338636,-1.819427 c 0.0069,2.498492 0.512192,5.059906 0.687257,7.557622 0.180437,2.574381 0.147584,5.437012 1.13217,7.837534 h 0.139957 c 0.01388,-5.031129 0.768741,-10.263802 1.399559,-15.255201 l 4.618547,1.679472 -2.659163,-3.918768 6.997803,-1.539515 V 51.998619 C 98.565088,50.830571 95.683088,50.57316 93.42892,50.039236 l 2.09934,-3.358943 -4.058723,1.679471 c -0.08379,-1.951841 -0.598717,-3.951518 -0.92181,-5.87815 -0.135668,-0.80901 -0.07139,-1.801661 -1.037574,-1.959384'/></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- AOS Library --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-seds-logo />
        </x-slot:brand>
        <x-slot:actions>
            @auth
                <livewire:notification-bell />
            @endauth
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-seds-logo class="px-5 pt-4 text-white" />

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-circle btn-ghost btn-xs" title="Logout">
                                    <x-icon name="o-power" />
                                </button>
                            </form>
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />

                    {{-- Dashboard --}}
                    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('dashboard') }}" />

                    {{-- Contributions --}}
                    <x-menu-item title="My Contributions" icon="o-chart-bar" link="{{ route('contributions.index') }}" />

                    {{-- Profile --}}
                    <x-menu-item title="My Profile" icon="o-user" link="{{ route('profile.show') }}" />
                    
                    {{-- Notifications --}}
                    <x-menu-item title="Notifications" icon="o-bell" link="{{ route('notifications') }}">
                        <x-slot:actions>
                            @php
                                $unreadCount = auth()->user()->notifications()->unread()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <x-badge value="{{ $unreadCount > 9 ? '9+' : $unreadCount }}" class="badge-error badge-sm" />
                            @endif
                        </x-slot:actions>
                    </x-menu-item>

                    {{-- Settings --}}
                    <x-menu-item title="Settings" icon="o-cog-6-tooth" link="{{ route('settings') }}" />

                    @if($user->isAdmin())
                        <x-menu-separator />

                        {{-- Admin Section --}}
                        <x-menu-sub title="Admin" icon="o-shield-check">
                            <x-menu-item title="Dashboard" icon="o-chart-pie" link="{{ route('admin.dashboard') }}" />
                            <x-menu-item title="Members" icon="o-users" link="{{ route('admin.members') }}" />
                            <x-menu-item title="Contributions" icon="o-chart-bar" link="{{ route('admin.contributions') }}" />
                            <x-menu-item title="Posts" icon="o-newspaper" link="{{ route('admin.posts') }}" />
                            <x-menu-item title="Activity Logs" icon="o-clipboard-document-list"
                                link="{{ route('admin.activity-logs') }}" />
                        </x-menu-sub>
                    @endif
                @endif
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>


    {{-- TOAST area --}}
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