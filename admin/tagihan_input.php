<?php
session_start();
include_once '../config/koneksi.php';

// Cek role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil list pasien dari tabel users
$pasienQuery = mysqli_query($conn, "SELECT id, username FROM users WHERE role='pasien' ORDER BY username ASC");

$errors = [];
$success = '';
$pasien_id = '';
$nominal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pasien_id = $_POST['pasien_id'] ?? '';
    $nominal = $_POST['nominal'] ?? '';

    if (!$pasien_id) $errors[] = 'Pasien harus dipilih.';
    if (!$nominal || !is_numeric($nominal) || $nominal <= 0) $errors[] = 'Nominal harus diisi dengan angka lebih dari 0.';

    if (!$errors) {
        $pasien_id = mysqli_real_escape_string($conn, $pasien_id);
        $nominal = mysqli_real_escape_string($conn, $nominal);

        $insert = mysqli_query($conn, "INSERT INTO tagihan_pembayaran (pasien_id, nominal) VALUES ('$pasien_id', '$nominal')");

        if ($insert) {
            $success = 'Tagihan berhasil ditambahkan.';
            $pasien_id = ''; // reset nilai setelah berhasil
            $nominal = '';
        } else {
            $errors[] = 'Gagal menambahkan tagihan: ' . mysqli_error($conn);
        }
    }
}
?>

<body class="bg-gray-50 flex min-h-screen font-modify">
    <?php include '../components/sidebar.php'; ?>

    <main class="flex-1 ml-64 p-8 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold mb-6 text-purple-700 text-center">Input Tagihan Pembayaran</h1>

        <?php if ($errors): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 border border-red-300">
            <?= implode('<br>', $errors) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-purple-100 text-purple-800 p-3 rounded mb-4 border border-purple-300">
            <?= $success ?>
        </div>
        <?php endif; ?>

        <div class="max-w-xl bg-white border border-purple-300 p-6 rounded-2xl shadow-md mx-auto space-y-6">
            <form method="POST" class="space-y-5">
                <div>
                    <label for="pasien_id" class="block font-semibold mb-2 text-purple-700">Pasien</label>
                    <select id="pasien_id" name="pasien_id" required
                        class="w-full border border-purple-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="">-- Pilih Pasien --</option>
                        <?php while ($row = mysqli_fetch_assoc($pasienQuery)): ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $pasien_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['username']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label for="nominal" class="block font-semibold mb-2 text-purple-700">Nominal Tagihan (Rp)</label>
                    <input type="number" id="nominal" name="nominal" min="1" step="0.01" required
                        value="<?= htmlspecialchars($nominal) ?>"
                        class="w-full border border-purple-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white"
                        placeholder="Masukkan nominal tagihan" />
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition font-semibold shadow">
                        Tambah Tagihan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>