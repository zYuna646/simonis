<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Simonis</title>
    @vite('resources/css/app.css')
    <style>
        .school-illustration {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-5xl w-full flex flex-col md:flex-row bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Ilustrasi Sekolah (Bagian Kiri) -->
            <div class="w-full md:w-1/2 bg-[var(--color-royal-blue-50)] p-6 flex items-center justify-center">
                <div class="text-center">
                    <svg class="school-illustration mx-auto" width="300" height="300" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M256 21.52l-256 128v42.48h32v192h128v-128h192v128h128v-192h32v-42.48l-256-128zm0 32.48l160 80v32h-320v-32l160-80zm-128 128h64v64h-64v-64zm192 0h64v64h-64v-64zm-224 80h64v64h-64v-64zm192 0h64v64h-64v-64zm-96 32h32v96h-32v-96z" fill="#4169E1"/>
                        <path d="M256 21.52l-256 128v42.48h32v192h128v-128h192v128h128v-192h32v-42.48l-256-128zm0 32.48l160 80v32h-320v-32l160-80zm-128 128h64v64h-64v-64zm192 0h64v64h-64v-64zm-224 80h64v64h-64v-64zm192 0h64v64h-64v-64zm-96 32h32v96h-32v-96z" fill="none" stroke="#1E3A8A" stroke-width="8"/>
                        <circle cx="256" cy="80" r="24" fill="#FFC107"/>
                        <rect x="112" y="384" width="288" height="16" fill="#4169E1"/>
                        <rect x="96" y="400" width="320" height="16" fill="#1E3A8A"/>
                    </svg>
                    <h2 class="text-xl font-bold text-[var(--color-royal-blue-800)] mt-4">Sistem Informasi Monitoring Siswa</h2>
                    <p class="text-[var(--color-royal-blue-600)] mt-2">Pantau perkembangan siswa dengan mudah dan efektif</p>
                </div>
            </div>
            
            <!-- Form Login (Bagian Kanan) -->
            <div class="w-full md:w-1/2">
                <div class="bg-[var(--color-royal-blue-600)] px-6 py-8">
                    <h1 class="text-2xl font-bold text-white text-center">Simonis</h1>
                    <p class="text-[var(--color-royal-blue-100)] text-center mt-2">Masuk ke akun Anda</p>
                </div>
                
                <div class="px-6 py-8">
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <x-auth.input 
                            type="email" 
                            name="email" 
                            label="Email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                        />
                        
                        <x-auth.input 
                            type="password" 
                            name="password" 
                            label="Password" 
                            required 
                        />
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox" class="h-4 w-4 text-[var(--color-royal-blue-600)] focus:ring-[var(--color-royal-blue-500)] border-gray-300 rounded" name="remember">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                        </div>
                        
                        <x-auth.button>
                            Masuk
                        </x-auth.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>