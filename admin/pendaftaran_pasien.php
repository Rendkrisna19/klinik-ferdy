<?php
session_start();
include_once '../config/koneksi.php';

// Cek role admin, contoh sederhana
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil list pasien dan dokter dari tabel users berdasarkan role
$pasienQuery = mysqli_query($conn, "SELECT id, username FROM users WHERE role = 'pasien' ORDER BY username ASC");
$dokterQuery = mysqli_query($conn, "SELECT id, username FROM users WHERE role = 'dokter' ORDER BY username ASC");

$errors = [];
$success = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pasien_id = $_POST['pasien_id'] ?? '';
    $dokter_id = $_POST['dokter_id'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $keluhan = trim($_POST['keluhan'] ?? '');

    if (!$pasien_id) $errors[] = 'Pasien harus dipilih.';
    if (!$dokter_id) $errors[] = 'Dokter harus dipilih.';
    if (!$tanggal) $errors[] = 'Tanggal harus diisi.';
    if (!$keluhan) $errors[] = 'Keluhan harus diisi.';

    if (!$errors) {
        $pasien_id = mysqli_real_escape_string($conn, $pasien_id);
        $dokter_id = mysqli_real_escape_string($conn, $dokter_id);
        $tanggal = mysqli_real_escape_string($conn, $tanggal);
        $keluhan = mysqli_real_escape_string($conn, $keluhan);

        $insert = mysqli_query($conn, "INSERT INTO pendaftaran (pasien_id, dokter_id, tanggal, keluhan) VALUES ('$pasien_id', '$dokter_id', '$tanggal', '$keluhan')");
        if ($insert) {
            $success = 'Pendaftaran konsultasi berhasil disimpan.';
        } else {
            $errors[] = 'Gagal menyimpan data pendaftaran: ' . mysqli_error($conn);
        }
    }
}
?>

<!-- Sidebar kiri -->

<body class="bg-white flex min-h-screen font-modify">

    <?php include '../components/sidebar.php'; ?>

    <!-- Konten kanan -->
    <main class="flex-1 ml-64 p-8 bg-white min-h-screen rounded-tl-3xl rounded-bl-3xl ">
        <h1 class="text-4xl font-extrabold mb-8 text-purple-700">Form Pendaftaran Konsultasi</h1>

        <?php if ($errors): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-lg border border-red-300 mb-6 shadow-md">
            <?= implode('<br>', $errors) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-green-100 text-green-800 p-4 rounded-lg border border-green-300 mb-6 shadow-md">
            <?= $success ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="max-w-2xl bg-purple-50 border border-purple-300 p-8 rounded-2xl shadow-lg space-y-6">
            <div>
                <label for="pasien_id" class="block font-semibold mb-2 text-purple-700">Pasien</label>
                <select id="pasien_id" name="pasien_id" required
                    class="w-full border border-purple-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white transition">
                    <option value="">-- Pilih Pasien --</option>
                    <?php
                mysqli_data_seek($pasienQuery, 0);
                while ($row = mysqli_fetch_assoc($pasienQuery)): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="dokter_id" class="block font-semibold mb-2 text-purple-700">Dokter</label>
                <select id="dokter_id" name="dokter_id" required
                    class="w-full border border-purple-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white transition">
                    <option value="">-- Pilih Dokter --</option>
                    <?php
                mysqli_data_seek($dokterQuery, 0);
                while ($row = mysqli_fetch_assoc($dokterQuery)): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="tanggal" class="block font-semibold mb-2 text-purple-700">Tanggal Konsultasi</label>
                <input type="date" id="tanggal" name="tanggal" required
                    class="w-full border border-purple-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white transition" />
            </div>

            <div>
                <label for="keluhan" class="block font-semibold mb-2 text-purple-700">Keluhan</label>
                <textarea id="keluhan" name="keluhan" rows="5" required
                    class="w-full border border-purple-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white transition"
                    placeholder="Tuliskan keluhan Anda..."></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-purple-700 text-white font-semibold px-8 py-3 rounded-xl hover:bg-purple-800 transition duration-300 shadow-md">
                    Daftar Konsultasi
                </button>
            </div>
        </form>
    </main>

    <style>
    body {
        display: flex;
        min-height: 100vh;
        background-color: #f3f4f6;
    }
    </style>