<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: /auth/login.php");
    exit;
}

$role = $_SESSION['user']['role'];
?>


<style>
@import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap');

.font-modify {
    font-family: "Lexend Deca", sans-serif;
}
</style>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Hamburger Script -->
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}
</script>

<!-- Hamburger Button (Mobile only) -->
<button onclick="toggleSidebar()" class="md:hidden p-4 text-purple-700 text-2xl">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<aside id="sidebar"
    class="w-64 h-screen bg-white text-purple-700 p-4 fixed font-modify transform md:translate-x-0 -translate-x-full transition-transform duration-300 z-50 shadow-md">
    <h2 class="text-3xl font-bold mb-6 text-purple-800">Klinik Ferdy</h2>

    <nav class="space-y-3 text-base">
        <?php if ($role === 'admin'): ?>
        <a href="../admin/index.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-chart-pie text-xl"></i> Dashboard
        </a>
        <a href="../admin/input_kelola.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-user-gear text-xl"></i> Kelola Pengguna
        </a>
        <a href="../admin/jadwal_dokter_admin.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-calendar-days text-xl"></i> Jadwal Dokter
        </a>
        <a href="../admin/pendaftaran_pasien.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-user-plus text-xl"></i> Pendaftaran Pasien
        </a>
        <a href="../admin/konsultasi_admin.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-comments text-xl"></i> Konsultasi Pasien
        </a>
        <a href="../admin/tagihan_input.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-money-bill-wave text-xl"></i> Tagihan
        </a>
        <a href="../admin/tagihan_kelola.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-file-invoice text-xl"></i> Daftar Tagihan
        </a>

        <?php elseif ($role === 'dokter'): ?>
        <a href="../dokter/index.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-house-medical text-xl"></i> Dashboard Dokter
        </a>
        <a href="../dokter/jadwal.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-calendar text-xl"></i> Jadwal Dokter
        </a>

        <a href="../dokter/konsultasi.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-stethoscope text-xl"></i> Konsultasi Pasien
        </a>
        <a href="../dokter/riwayat_rekammedis.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-file-medical text-xl"></i> Hystory Rekam Medis
        </a>

        <?php elseif ($role === 'resepsionis'): ?>
        <a href="../resepsionis/index.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-desktop text-xl"></i> Dashboard Resepsionis
        </a>
        <a href="../resepsionis/pendaftaran_offline.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-user-plus text-xl"></i> Pendaftaran Pasien
        </a>
        <a href="../resepsionis/jadwal_dokter.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-calendar-days text-xl"></i> Jadwal Dokter
        </a>
        <a href="../resepsionis/daftar_pendaftaran.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-users text-xl"></i> Daftar Pendaftaran Offline
        </a>


        <?php elseif ($role === 'pasien'): ?>
        <a href="../pasien/index.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-house-user text-xl"></i> Dashboard
        </a>
        <a href="../pasien/lihat_jadwal.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-calendar-alt text-xl"></i> Lihat Jadwal
        </a>
        <a href="../pasien/konsultasi_dokter.php"
            class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-headset text-xl"></i> Konsultasi Ke dokter
        </a>
        <a href="../pasien/rekam_medis.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-notes-medical text-xl"></i> Rekam Medis Saya
        </a>
        <a href="../pasien/lihat_tagihan.php" class="flex items-center gap-3 hover:bg-purple-100 p-3 rounded text-lg">
            <i class="fas fa-receipt text-xl"></i> Pembayaran
        </a>
        <?php endif; ?>

        <a href="../auth/login.php"
            class="flex items-center justify-center hover:bg-purple-600 p-3 rounded mt-4 bg-purple-500 text-white text-lg">
            <i class="fas fa-sign-out-alt mr-2 text-xl"></i> Logout
        </a>
    </nav>
</aside>