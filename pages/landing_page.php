<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Informasi Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2d9a4bd63.js" crossorigin="anonymous"></script>

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap');

    .font-modify {
        font-family: "Lexend Deca", sans-serif;
    }
    </style>
</head>

<body class="bg-white text-gray-800 font-modify">

    <!-- Navbar -->
    <header class="bg-purple-700 text-white shadow-lg sticky top-0 z-50" data-aos="fade-down">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">KlinikSehat</h1>
            <nav class="space-x-6 hidden md:flex">
                <a href="#home" class="hover:text-purple-300">Beranda</a>
                <a href="#features" class="hover:text-purple-300">Fitur</a>
                <a href="#about" class="hover:text-purple-300">Tentang</a>
                <a href="#contact" class="hover:text-purple-300">Kontak</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="bg-purple-50 py-16" data-aos="fade-up">
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row items-center px-6 space-y-10 md:space-y-0 md:space-x-10">
            <div class="md:w-1/2 space-y-6" data-aos="fade-right">
                <h2 class="text-4xl font-bold text-purple-700">Sistem Informasi Klinik Modern</h2>
                <p class="text-lg text-gray-600">Kelola pasien, jadwal, dan rekam medis dengan mudah dalam satu platform
                    terpadu.</p>
                <a href="../auth/register.php"
                    class="inline-block bg-purple-700 text-white px-6 py-3 rounded-full hover:bg-purple-800 transition">Mulai
                    Sekarang</a>
            </div>
            <div class="md:w-1/2" data-aos="fade-left">
                <img src="https://grhakumalaclinic.com/wp-content/uploads/2024/02/Foto-Edit-1024x768.jpg" alt="Dokter"
                    class="rounded-lg shadow-lg" />
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-20 bg-white" data-aos="fade-up">
        <div class="max-w-6xl mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-purple-700 mb-12" data-aos="zoom-in">Fitur Unggulan</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-purple-50 rounded-lg shadow hover:shadow-lg transition"
                    data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-calendar-check text-4xl text-purple-700 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Booking Janji Temu</h4>
                    <p class="text-gray-600">Pasien dapat membuat janji temu secara online tanpa harus datang langsung.
                    </p>
                </div>
                <div class="text-center p-6 bg-purple-50 rounded-lg shadow hover:shadow-lg transition"
                    data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-user-md text-4xl text-purple-700 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Manajemen Pasien</h4>
                    <p class="text-gray-600">Data pasien tersimpan rapi dan mudah diakses oleh dokter dan admin.</p>
                </div>
                <div class="text-center p-6 bg-purple-50 rounded-lg shadow hover:shadow-lg transition"
                    data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-file-medical-alt text-4xl text-purple-700 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Rekam Medis Digital</h4>
                    <p class="text-gray-600">Catatan kesehatan pasien tersedia lengkap dan aman dalam sistem.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="py-20 bg-purple-100" data-aos="fade-up">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold text-purple-700 mb-6">Tentang Sistem Kami</h3>
            <p class="text-lg text-gray-700 max-w-3xl mx-auto">Kami berkomitmen memberikan solusi digital terbaik untuk
                klinik dan pusat layanan kesehatan agar proses administrasi dan pelayanan menjadi lebih efisien dan
                terstruktur.</p>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-purple-700 mb-12" data-aos="zoom-in">Testimoni Pengguna</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-purple-50 p-6 rounded-lg shadow" data-aos="fade-right">
                    <p class="text-gray-700 italic">"Sangat membantu! Sekarang saya bisa melihat jadwal dan rekam medis
                        pasien dengan mudah."</p>
                    <div class="mt-4 flex items-center space-x-4">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-12 h-12 rounded-full" />
                        <div>
                            <p class="font-semibold">Dr. Fitri Rahma</p>
                            <p class="text-sm text-gray-500">Dokter Umum</p>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg shadow" data-aos="fade-left">
                    <p class="text-gray-700 italic">"Sistemnya user-friendly dan sangat mempermudah pekerjaan kami di
                        resepsionis."</p>
                    <div class="mt-4 flex items-center space-x-4">
                        <img src="https://randomuser.me/api/portraits/men/35.jpg" class="w-12 h-12 rounded-full" />
                        <div>
                            <p class="font-semibold">Andi Kurniawan</p>
                            <p class="text-sm text-gray-500">Staf Klinik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-purple-700 text-white text-center" data-aos="zoom-in">
        <h3 class="text-3xl font-bold mb-4">Siap untuk digitalisasi klinik Anda?</h3>
        <p class="mb-6">Gabung sekarang dan nikmati kemudahan dalam pelayanan kesehatan modern.</p>
        <a href="#contact"
            class="bg-white text-purple-700 px-6 py-3 rounded-full font-semibold hover:bg-purple-200 transition">Hubungi
            Kami</a>
    </section>

    <!-- Footer -->
    <footer class="bg-purple-900 text-white py-10 mt-10" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-lg font-bold mb-3">KlinikSehat</h4>
                <p class="text-sm">Sistem informasi modern untuk klinik dan praktik dokter.</p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-3">Menu</h4>
                <ul class="text-sm space-y-2">
                    <li><a href="#home" class="hover:underline">Beranda</a></li>
                    <li><a href="#features" class="hover:underline">Fitur</a></li>
                    <li><a href="#about" class="hover:underline">Tentang</a></li>
                    <li><a href="#contact" class="hover:underline">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-3">Kontak</h4>
                <p class="text-sm"><i class="fas fa-envelope mr-2"></i> support@kliniksehat.id</p>
                <p class="text-sm"><i class="fas fa-phone mr-2"></i> +62 812-3456-7890</p>
                <div class="mt-3 space-x-4 text-lg">
                    <a href="#"><i class="fab fa-facebook hover:text-purple-300"></i></a>
                    <a href="#"><i class="fab fa-instagram hover:text-purple-300"></i></a>
                    <a href="#"><i class="fab fa-twitter hover:text-purple-300"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center mt-10 text-sm text-purple-300">© 2025 Klinik Ferdy. All rights reserved.</div>
    </footer>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 1000,
        once: true
    });
    </script>

</body>

</html>