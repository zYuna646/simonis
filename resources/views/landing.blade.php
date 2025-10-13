@extends('layouts.landing')

@section('title', 'Selamat Datang di SIMONIS')

@section('content')
<!-- Hero Section -->
<div class="py-12 bg-[var(--color-royal-blue-50)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:flex lg:items-center lg:justify-between">
            <div class="lg:w-1/2">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">
                    Sistem Informasi Monitoring Siswa
                </h1>
                <p class="mt-3 text-lg text-gray-600">
                    Platform terpadu untuk memantau perkembangan akademik dan kehadiran siswa secara real-time.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4">
                    <a href="{{ route('login') }}" class="bg-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-700)] text-white px-6 py-3 rounded-md font-medium mb-3 sm:mb-0 text-center">
                        Daftar Sekarang
                    </a>
                    <a href="#features" class="border border-[var(--color-royal-blue-600)] text-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-50)] px-6 py-3 rounded-md font-medium text-center">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="mt-10 lg:mt-0 lg:w-1/2">
                <img src="https://img.freepik.com/free-vector/online-learning-isometric-concept_1284-17947.jpg" alt="Sistem Informasi Sekolah" class="rounded-lg shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Fitur Unggulan</h2>
            <p class="mt-4 text-lg text-gray-600">
                Solusi lengkap untuk pengelolaan dan pemantauan siswa
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Feature Card 1 -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-[var(--color-royal-blue-100)] rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-user-graduate text-[var(--color-royal-blue-600)] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Manajemen Siswa</h3>
                <p class="mt-2 text-gray-600">
                    Kelola data siswa dengan mudah, termasuk profil, kelas, dan riwayat akademik.
                </p>
                <a href="#" class="mt-4 inline-block text-[var(--color-royal-blue-600)] hover:text-[var(--color-royal-blue-700)]">
                    Selengkapnya &rarr;
                </a>
            </div>

            <!-- Feature Card 2 -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-[var(--color-royal-blue-100)] rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-[var(--color-royal-blue-600)] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Pemantauan Nilai</h3>
                <p class="mt-2 text-gray-600">
                    Pantau perkembangan nilai siswa secara real-time dengan visualisasi yang informatif.
                </p>
                <a href="#" class="mt-4 inline-block text-[var(--color-royal-blue-600)] hover:text-[var(--color-royal-blue-700)]">
                    Selengkapnya &rarr;
                </a>
            </div>

            <!-- Feature Card 3 -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-[var(--color-royal-blue-100)] rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-check text-[var(--color-royal-blue-600)] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Kehadiran Siswa</h3>
                <p class="mt-2 text-gray-600">
                    Catat dan analisis kehadiran siswa untuk meningkatkan kedisiplinan dan performa.
                </p>
                <a href="#" class="mt-4 inline-block text-[var(--color-royal-blue-600)] hover:text-[var(--color-royal-blue-700)]">
                    Selengkapnya &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<div id="about" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:flex lg:items-center lg:gap-16">
            <div class="lg:w-1/2">
                <img src="https://img.freepik.com/free-vector/school-building-educational-institution-college_107791-1051.jpg" alt="Tentang SIMONIS" class="rounded-lg shadow-lg">
            </div>
            <div class="mt-10 lg:mt-0 lg:w-1/2">
                <h2 class="text-3xl font-bold text-gray-900">Tentang SIMONIS</h2>
                <p class="mt-4 text-lg text-gray-600">
                    SIMONIS (Sistem Informasi Monitoring Siswa) adalah platform terpadu yang dirancang untuk memudahkan pengelolaan dan pemantauan perkembangan siswa di sekolah.
                </p>
                <p class="mt-4 text-lg text-gray-600">
                    Dengan SIMONIS, guru dan orang tua dapat berkolaborasi dalam memantau perkembangan akademik dan kehadiran siswa, sehingga dapat memberikan dukungan yang tepat untuk meningkatkan prestasi belajar.
                </p>
                <div class="mt-8">
                    <a href="#contact" class="bg-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-700)] text-white px-6 py-3 rounded-md font-medium inline-block">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Testimoni</h2>
            <p class="mt-4 text-lg text-gray-600">
                Apa kata pengguna tentang SIMONIS
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Testimonial 1 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">Budi Santoso</h4>
                        <p class="text-sm text-gray-500">Kepala Sekolah</p>
                    </div>
                </div>
                <p class="text-gray-600">
                    "SIMONIS telah membantu kami meningkatkan efisiensi pengelolaan data siswa dan mempermudah pemantauan perkembangan akademik."
                </p>
                <div class="mt-4 flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">Siti Rahayu</h4>
                        <p class="text-sm text-gray-500">Guru Matematika</p>
                    </div>
                </div>
                <p class="text-gray-600">
                    "Dengan SIMONIS, saya dapat dengan mudah melacak kemajuan siswa dan memberikan umpan balik yang tepat waktu kepada mereka dan orang tua."
                </p>
                <div class="mt-4 flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold">Dewi Lestari</h4>
                        <p class="text-sm text-gray-500">Orang Tua Siswa</p>
                    </div>
                </div>
                <p class="text-gray-600">
                    "Sebagai orang tua, SIMONIS memberi saya visibilitas yang lebih baik tentang perkembangan anak saya di sekolah. Sangat membantu!"
                </p>
                <div class="mt-4 flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div id="contact" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:flex lg:items-center lg:gap-16">
            <div class="lg:w-1/2">
                <h2 class="text-3xl font-bold text-gray-900">Hubungi Kami</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Punya pertanyaan atau ingin tahu lebih banyak tentang SIMONIS? Jangan ragu untuk menghubungi kami.
                </p>
                
                <div class="mt-8 space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-[var(--color-royal-blue-600)] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold">Alamat</h4>
                            <p class="text-gray-600">Jl. Pendidikan No. 123, Jakarta Selatan, Indonesia</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-phone text-[var(--color-royal-blue-600)] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold">Telepon</h4>
                            <p class="text-gray-600">+62 21 1234 5678</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-[var(--color-royal-blue-600)] text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold">Email</h4>
                            <p class="text-gray-600">info@simonis.id</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 lg:mt-0 lg:w-1/2">
                <form class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[var(--color-royal-blue-500)] focus:border-[var(--color-royal-blue-500)]">
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[var(--color-royal-blue-500)] focus:border-[var(--color-royal-blue-500)]">
                    </div>
                    
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                        <input type="text" id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[var(--color-royal-blue-500)] focus:border-[var(--color-royal-blue-500)]">
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-[var(--color-royal-blue-500)] focus:border-[var(--color-royal-blue-500)]"></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-700)] text-white px-6 py-3 rounded-md font-medium">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-[var(--color-royal-blue-600)] py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Siap untuk memulai?</h2>
            <p class="mt-4 text-xl text-[var(--color-royal-blue-100)]">
                Bergabunglah dengan ribuan sekolah yang telah menggunakan SIMONIS
            </p>
            <div class="mt-8">
                <a href="{{ route('login') }}" class="bg-white text-[var(--color-royal-blue-600)] hover:bg-[var(--color-royal-blue-50)] px-6 py-3 rounded-md font-medium inline-block">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection