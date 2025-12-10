<x-layouts.guest>
    <x-slot:title>Welcome to SEDS Mora</x-slot:title>
<div class="min-h-screen relative overflow-hidden flex items-center justify-center px-4">
    {{-- Space Background from Unsplash --}}
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
         style="background-image: url('https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?q=80&w=2013&auto=format&fit=crop');">
    </div>

    {{-- Dark overlay for better text readability --}}
    <div class="absolute inset-0 bg-black/50"></div>

    {{-- Subtle gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-b from-primary/20 via-transparent to-primary/30"></div>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto text-center relative z-10">
        {{-- Hero Section --}}
        <div data-aos="fade-up">
            {{-- Logo --}}
            <div class="flex justify-center mb-8">
                <x-app-logo-icon class="w-32 h-32 text-white" />
            </div>

            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
                SEDS Mora
            </h1>
            <p class="text-xl md:text-2xl text-white/90 mb-4">
                Students for Exploration and Development of Space
            </p>
            <p class="text-lg text-white/70 mb-12">
                University of Moratuwa
            </p>
        </div>

        {{-- Description --}}
        <div data-aos="fade-up" data-aos-delay="200">
            <p class="text-lg text-white/80 mb-12 max-w-2xl mx-auto">
                Join our community of passionate students exploring the frontiers of space science and technology.
                Connect, collaborate, and contribute to exciting space-related projects and activities.
            </p>
        </div>

        {{-- CTA Buttons --}}
        <div data-aos="fade-up" data-aos-delay="400" class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg shadow-sm hover:shadow-md transition-shadow">
                <x-icon name="o-arrow-right-on-rectangle" class="w-6 h-6" />
                Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline btn-primary btn-lg shadow-sm hover:shadow-md transition-shadow">
                <x-icon name="o-user-plus" class="w-6 h-6" />
                Register
            </a>
        </div>

        {{-- Features --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20">
            <div data-aos="fade-up" data-aos-delay="600" class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <x-icon name="o-users" class="w-12 h-12 text-primary mx-auto" />
                    <h3 class="card-title justify-center">Community</h3>
                    <p>Connect with fellow space enthusiasts</p>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="700" class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <x-icon name="o-rocket-launch" class="w-12 h-12 text-secondary mx-auto" />
                    <h3 class="card-title justify-center">Projects</h3>
                    <p>Participate in exciting space projects</p>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="800" class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="card-body">
                    <x-icon name="o-chart-bar" class="w-12 h-12 text-accent mx-auto" />
                    <h3 class="card-title justify-center">Track Progress</h3>
                    <p>Log and showcase your contributions</p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.guest>
