<?php
session_start();
include_once '../config/koneksi.php';

// Pastikan user sudah login dan role-nya dokter atau admin
if (!isset($_SESSION['user'])) {
    header("Location: /auth/login.php");
    exit;
}

$role = $_SESSION['user']['role'];
if (!in_array($role, ['admin', 'dokter'])) {
    die("Anda tidak punya akses ke halaman ini.");
}

// Ambil semua dokter (untuk dropdown)
$dokterResult = $conn->query("SELECT id, username FROM users WHERE role='dokter'");

// Handle form tambah/edit
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $dokter_id = $_POST['dokter_id'] ?? null;
    $hari = $_POST['hari'] ?? null;
    $jam_mulai = $_POST['jam_mulai'] ?? null;
    $jam_selesai = $_POST['jam_selesai'] ?? null;

    if (!$dokter_id || !$hari || !$jam_mulai || !$jam_selesai) {
        $errors[] = "Semua field wajib diisi.";
    }

    if (!$errors) {
        if ($id) {
            // Update
            $stmt = $conn->prepare("UPDATE jadwal_dokter SET dokter_id=?, hari=?, jam_mulai=?, jam_selesai=? WHERE id=?");
            $stmt->bind_param("isssi", $dokter_id, $hari, $jam_mulai, $jam_selesai, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $dokter_id, $hari, $jam_mulai, $jam_selesai);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: jadwal_dokter_admin.php");
        exit;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $idDelete = intval($_GET['delete']);
    $conn->query("DELETE FROM jadwal_dokter WHERE id = $idDelete");
    header("Location: jadwal_dokter_admin.php");
    exit;
}

// Ambil data jadwal dokter
$result = $conn->query("SELECT jd.*, u.username AS nama_dokter FROM jadwal_dokter jd JOIN users u ON jd.dokter_id = u.id ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Jadwal Dokter - Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex min-h-screen bg-white font-modify">

    <!-- Sidebar -->
    <?php include_once '../components/sidebar.php'; ?>

    <!-- Konten utama -->
    <main class="ml-64 p-8">
        <h1 class="text-4xl font-extrabold text-purple-700 mb-8">Jadwal Dokter</h1>

        <?php if ($errors): ?>
        <div class="bg-purple-100 text-purple-800 p-4 rounded-md mb-6 shadow-md">
            <?= implode('<br>', $errors) ?>
        </div>
        <?php endif; ?>

        <!-- Button tambah jadwal -->
        <button onclick="openModal()"
            class="mb-8 bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition shadow-md">
            + Tambah Jadwal
        </button>

        <div class="overflow-x-auto bg-white shadow-lg rounded-xl border border-purple-300">
            <table class="w-full text-sm text-left text-purple-900">
                <thead class="bg-purple-200 text-purple-900 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-8 py-4 font-semibold">Dokter</th>
                        <th class="px-8 py-4 font-semibold">Hari</th>
                        <th class="px-8 py-4 font-semibold">Jam Mulai</th>
                        <th class="px-8 py-4 font-semibold">Jam Selesai</th>
                        <th class="px-8 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-purple-300 hover:bg-purple-50 transition">
                        <td class="px-8 py-4"><?= htmlspecialchars($row['nama_dokter']) ?></td>
                        <td class="px-8 py-4"><?= $row['hari'] ?></td>
                        <td class="px-8 py-4"><?= substr($row['jam_mulai'], 0, 5) ?></td>
                        <td class="px-8 py-4"><?= substr($row['jam_selesai'], 0, 5) ?></td>
                        <td class="px-8 py-4 flex gap-6">
                            <button
                                onclick="openModal(<?= $row['id'] ?>, <?= $row['dokter_id'] ?>, '<?= $row['hari'] ?>', '<?= substr($row['jam_mulai'], 0, 5) ?>', '<?= substr($row['jam_selesai'], 0, 5) ?>')"
                                class="text-purple-600 hover:underline font-semibold transition">Edit</button>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus jadwal ini?')"
                                class="text-red-600 hover:underline font-semibold transition">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Form -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center  z-50">
            <div
                class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto transform transition-transform scale-90 opacity-0 modal-transition">
                <h2 id="modalTitle" class="text-3xl font-bold text-purple-700 mb-6">Tambah Jadwal</h2>
                <form id="jadwalForm" method="POST" action="">
                    <input type="hidden" name="id" id="jadwal_id" />

                    <div class="mb-5">
                        <label for="dokter_id" class="block text-gray-700 mb-2 font-semibold">Dokter:</label>
                        <select name="dokter_id" id="dokter_id"
                            class="border border-purple-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500"
                            required>
                            <option value="">-- Pilih Dokter --</option>
                            <?php foreach ($dokterResult as $dokter): ?>
                            <option value="<?= $dokter['id'] ?>"><?= htmlspecialchars($dokter['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label for="hari" class="block text-gray-700 mb-2 font-semibold">Hari:</label>
                        <select name="hari" id="hari"
                            class="border border-purple-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500"
                            required>
                            <option value="">-- Pilih Hari --</option>
                            <?php
                        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        foreach ($hariOptions as $hariOption): ?>
                            <option value="<?= $hariOption ?>"><?= $hariOption ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label for="jam_mulai" class="block text-gray-700 mb-2 font-semibold">Jam Mulai:</label>
                        <input type="time" name="jam_mulai" id="jam_mulai"
                            class="border border-purple-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500"
                            required />
                    </div>

                    <div class="mb-8">
                        <label for="jam_selesai" class="block text-gray-700 mb-2 font-semibold">Jam Selesai:</label>
                        <input type="time" name="jam_selesai" id="jam_selesai"
                            class="border border-purple-300 rounded-lg px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500"
                            required />
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closeModal()"
                            class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    function openModal(id = '', dokter_id = '', hari = '', jam_mulai = '', jam_selesai = '') {
        const modal = document.getElementById('modal');
        const modalContent = modal.querySelector('div');

        document.getElementById('jadwal_id').value = id;
        document.getElementById('dokter_id').value = dokter_id;
        document.getElementById('hari').value = hari;
        document.getElementById('jam_mulai').value = jam_mulai;
        document.getElementById('jam_selesai').value = jam_selesai;
        document.getElementById('modalTitle').innerText = id ? 'Edit Jadwal' : 'Tambah Jadwal';

        modal.classList.remove('hidden');
        // Animate modal in
        setTimeout(() => {
            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        const modalContent = modal.querySelector('div');

        // Animate modal out
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
    </script>

</body>

</html>