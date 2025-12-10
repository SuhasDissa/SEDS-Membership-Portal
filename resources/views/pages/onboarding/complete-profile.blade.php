<x-layouts.guest>
    <x-slot:title>Complete Your Profile</x-slot:title>

    <div class="min-h-screen bg-base-200 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <div>
                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <x-app-logo-icon class="w-20 h-20 text-primary" />
                    </div>
                    <h1 class="text-4xl font-bold text-primary mb-2">Welcome to SEDS Mora!</h1>
                    <p class="text-base-content/70">Please complete your profile to continue</p>
                </div>

                {{-- Profile Completion Form Component --}}
                <livewire:onboarding.complete-profile />
            </div>
        </div>
    </div>
</x-layouts.guest>
