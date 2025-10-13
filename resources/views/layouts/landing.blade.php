<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    @vite('resources/css/app.css')

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('styles')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="text-2xl font-semibold text-[var(--color-royal-blue-600)]">
                            {{ config('app.name', 'Laravel') }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('landing') }}"
                            class="text-gray-600 hover:text-[var(--color-royal-blue-600)]">Home</a>
                        <a href="#features" class="text-gray-600 hover:text-[var(--color-royal-blue-600)]">Features</a>
                        <a href="#about" class="text-gray-600 hover:text-[var(--color-royal-blue-600)]">About</a>
                        <a href="#contact" class="text-gray-600 hover:text-[var(--color-royal-blue-600)]">Contact</a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="bg-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-700)] text-white px-4 py-2 rounded-md">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-gray-600 hover:text-[var(--color-royal-blue-600)]">Login</a>
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}"
                                        class="bg-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-700)] text-white px-4 py-2 rounded-md">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="bg-gray-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-white text-lg font-semibold mb-4">Company</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white">About</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Careers</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Blog</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Press</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold mb-4">Products</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white">Features</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Pricing</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Documentation</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">API</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white">Help Center</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Community</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Partners</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Status</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold mb-4">Connect</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white"><i
                                        class="fab fa-twitter mr-2"></i>Twitter</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white"><i
                                        class="fab fa-facebook mr-2"></i>Facebook</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white"><i
                                        class="fab fa-instagram mr-2"></i>Instagram</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white"><i
                                        class="fab fa-linkedin mr-2"></i>LinkedIn</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700">
                    <p class="text-gray-400 text-center">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @yield('scripts')
</body>

</html>
