<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Verification - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">
    <div class="min-h-screen bg-base-200 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div data-aos="fade-up">
                {{-- Logo/Title --}}
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <x-app-logo-icon class="w-20 h-20 text-primary" />
                    </div>
                    <h1 class="text-4xl font-bold text-primary mb-2">Verify Your Email</h1>
                    <p class="text-base-content/70">Check your inbox to continue</p>
                </div>

                {{-- Verification Card --}}
                <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                <x-icon name="o-check-circle" class="w-5 h-5" />
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <div class="text-center mb-6">
                            <x-icon name="o-envelope" class="w-16 h-16 mx-auto text-primary mb-4" />
                            <p class="text-base-content/70">
                                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full">
                                <x-icon name="o-arrow-path" class="w-5 h-5" />
                                Resend Verification Email
                            </button>
                        </form>

                        <div class="divider">OR</div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline w-full">
                                <x-icon name="o-arrow-right-on-rectangle" class="w-5 h-5" />
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Back to Home --}}
                <div class="text-center mt-4">
                    <a href="{{ route('landing') }}" class="link link-primary">
                        <x-icon name="o-arrow-left" class="w-4 h-4 inline" />
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{--  TOAST area --}}
    <x-toast />
</body>
</html>
