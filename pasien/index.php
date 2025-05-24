<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pasien') {
    header('Location: /auth/login.php');
    exit;
}
$username = $_SESSION['user']['username']; // misal ada username di session
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Pasien - Klinik Ferdy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    modify: ['Inter', 'sans-serif'],
                },
                colors: {
                    primary: '#7C3AED', // Ungu Tailwind (purple-600)
                }
            }
        }
    };
    </script>
</head>

<body class="bg-gray-100 font-modify flex min-h-screen">

    <?php include '../components/sidebar.php'; ?>

    <!-- Konten utama -->
    <main class="flex-1 ml-64 p-10 bg-white rounded-tl-3xl shadow-xl">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row items-center justify-between bg-primary text-white p-6 rounded-xl mb-10 shadow-md">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold">Selamat Datang, <?= htmlspecialchars($username) ?> 👋</h1>
                <p class="mt-2 text-sm md:text-base">Anda berada di halaman dashboard pasien Klinik Ferdy.</p>
            </div>
            <img src="https://png.pngtree.com/png-vector/20230808/ourmid/pngtree-patient-clipart-cartoon-male-doctor-with-clipboard-and-stethoscope-on-a-vector-png-image_6819503.png"
                alt="Ilustrasi Dokter" class="w-40 h-40 mt-6 md:mt-0 md:w-48 md:h-48 object-contain" />
        </div>

        <!-- Tata Cara Pendaftaran & Konsultasi -->
        <!-- Tata Cara Pendaftaran & Konsultasi -->
        <div class="bg-gray-50 p-8 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-primary flex items-center gap-2 mb-5">
                <i class="fas fa-info-circle text-primary text-xl"></i>
                Tata Cara Pendaftaran, Konsultasi, dan Pembayaran
            </h2>
            <ol class="list-decimal list-inside text-gray-700 space-y-3 leading-relaxed text-[16px]">
                <li>
                    <i class="fas fa-clipboard-list text-primary mr-2"></i>
                    <strong>Pendaftaran:</strong> Pasien melakukan pendaftaran melalui menu <em>Pendaftaran</em> di
                    dashboard.
                    Isikan data diri lengkap dan pilih jadwal yang tersedia.
                </li>
                <li>
                    <i class="fas fa-user-md text-primary mr-2"></i>
                    <strong>Konsultasi:</strong> Datang ke klinik sesuai jadwal yang telah dipilih atau lakukan
                    konsultasi online
                    jika tersedia.
                </li>
                <li>
                    <i class="fas fa-credit-card text-primary mr-2"></i>
                    <strong>Pembayaran:</strong> Pembayaran dilakukan langsung di klinik atau melalui transfer ke
                    rekening resmi
                    Klinik Ferdy. Bukti pembayaran wajib diunggah di menu <em>Pembayaran</em>.
                </li>
                <li>
                    <i class="fas fa-file-medical text-primary mr-2"></i>
                    <strong>Rekam Medis:</strong> Setelah konsultasi, hasil akan tersedia di menu <em>Rekam Medis
                        Saya</em>
                    untuk dapat dilihat dan dicetak.
                </li>
            </ol>
        </div>


    </main>
</body>

</html>