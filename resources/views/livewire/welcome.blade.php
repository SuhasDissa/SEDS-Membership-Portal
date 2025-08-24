<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.registration')] class extends Component {};
?>

<main class="relative">
    <!-- Hero Section -->
    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-4xl">
                <h1 class="text-5xl lg:text-7xl font-bold mb-6 text-base-content">
                    Space Exploration &<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                        Development Society
                    </span>
                </h1>

                <p class="text-xl lg:text-2xl text-base-content/70 mb-4 mx-auto">University of Moratuwa</p>

                <p class="text-lg text-base-content/60 max-w-2xl mx-auto mb-12">
                    Join us in our mission to explore space technologies, conduct research,
                    and inspire the next generation of engineers and scientists.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16">
                    <x-mary-button link="{{ route('member.register') }}" class="btn-primary btn-lg">
                        <x-mary-icon name="o-user-plus" class="w-5 h-5" />
                        Join SEDS Today
                    </x-mary-button>
                </div>
            </div>
        </div>
    </div>
</main>
