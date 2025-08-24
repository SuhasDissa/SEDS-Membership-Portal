<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="min-h-screen flex">
            <!-- Sidebar (desktop) -->
            <aside class="w-80 hidden lg:flex flex-col min-h-screen bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
                <!-- Logo -->
                <div class="p-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <x-app-logo />
                    </a>
                </div>

                <!-- Navigation Menu -->
                <x-mary-menu class="px-4">
                    <x-mary-menu-item 
                            title="{{ __('Dashboard') }}" 
                            icon="o-home" 
                            link="{{ route('dashboard') }}"
                            :active="request()->routeIs('dashboard')"
                        />
                        <x-mary-menu-item 
                            title="{{ __('Members') }}" 
                            icon="o-users" 
                            link="{{ route('admin.members') }}"
                            :active="request()->is('dashboard/members')"
                        />
                </x-mary-menu>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- External Links -->
                <x-mary-menu class="px-4 mb-4">
                    <x-mary-menu-item 
                        title="{{ __('Repository') }}" 
                        icon="o-folder" 
                        link="https://github.com/laravel/livewire-starter-kit"
                        external
                    />
                    <x-mary-menu-item 
                        title="{{ __('Documentation') }}" 
                        icon="o-book-open" 
                        link="https://laravel.com/docs/starter-kits#livewire"
                        external
                    />
                </x-mary-menu>

                <!-- Desktop User Menu -->
                <div class="p-4">
                    <x-mary-dropdown>
                        <x-slot name="trigger">
                            <x-mary-button class="btn-ghost w-full justify-start">
                                <x-mary-avatar 
                                    :name="auth()->user()->name"
                                    class="!w-8 !h-8"
                                />
                                <div class="ml-3 text-left">
                                    <div class="font-semibold text-sm">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                </div>
                                <x-mary-icon name="o-chevron-up-down" class="ml-auto w-4 h-4" />
                            </x-mary-button>
                        </x-slot>

                        <!-- User Info Display -->
                        <div class="px-4 py-3 border-b">
                            <div class="flex items-center gap-3">
                                <x-mary-avatar 
                                    :name="auth()->user()->name"
                                    class="!w-8 !h-8"
                                />
                                <div>
                                    <div class="font-semibold text-sm">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>

                        <x-mary-menu-item 
                            title="{{ __('Settings') }}" 
                            icon="o-cog-6-tooth" 
                            link="{{ route('settings.profile') }}"
                        />
                        
                        <x-mary-menu-separator />
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-mary-menu-item 
                                title="{{ __('Log Out') }}" 
                                icon="o-arrow-right-on-rectangle"
                                onclick="this.closest('form').submit()"
                            />
                        </form>
                    </x-mary-dropdown>
                </div>
            </aside>

            <!-- Main column -->
            <div class="flex-1 min-h-screen flex flex-col">
                <!-- Mobile Header -->
                <x-mary-nav class="lg:hidden bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700" sticky>
                    <x-slot:brand>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                            <x-app-logo />
                        </a>
                    </x-slot:brand>

                    <x-slot:actions>
                        <x-mary-dropdown>
                            <x-slot:trigger>
                                <x-mary-button class="btn-ghost btn-sm">
                                    <x-mary-avatar 
                                        :name="auth()->user()->name"
                                        class="!w-6 !h-6"
                                    />
                                    <x-mary-icon name="o-chevron-down" class="w-4 h-4 ml-2" />
                                </x-mary-button>
                            </x-slot:trigger>

                            <!-- User Info Display -->
                            <div class="px-4 py-3 border-b">
                                <div class="flex items-center gap-3">
                                    <x-mary-avatar 
                                        :name="auth()->user()->name"
                                        class="!w-8 !h-8"
                                    />
                                    <div>
                                        <div class="font-semibold text-sm">{{ auth()->user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <x-mary-menu-item 
                                title="{{ __('Settings') }}" 
                                icon="o-cog-6-tooth" 
                                link="{{ route('settings.profile') }}"
                            />
                            
                            <x-mary-menu-separator />
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-mary-menu-item 
                                    title="{{ __('Log Out') }}" 
                                    icon="o-arrow-right-on-rectangle"
                                    onclick="this.closest('form').submit()"
                                />
                            </form>
                        </x-mary-dropdown>
                    </x-slot:actions>
                </x-mary-nav>

                <!-- Page content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>