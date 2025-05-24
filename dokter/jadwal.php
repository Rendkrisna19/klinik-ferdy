<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dokter') {
    header('Location: /auth/login.php');
    exit;
}

include_once '../config/koneksi.php';

$dokter_id = $_SESSION['user']['id'];

// Handle tambah / edit jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $hari = $_POST['hari'] ?? '';
    $jam_mulai = $_POST['jam_mulai'] ?? '';
    $jam_selesai = $_POST['jam_selesai'] ?? '';

    $allowed_days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    if (in_array($hari, $allowed_days) && $jam_mulai && $jam_selesai) {
        if ($_POST['action'] === 'tambah') {
            $stmt = $conn->prepare("INSERT INTO jadwal_dokter (dokter_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $dokter_id, $hari, $jam_mulai, $jam_selesai);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("UPDATE jadwal_dokter SET hari = ?, jam_mulai = ?, jam_selesai = ? WHERE id = ? AND dokter_id = ?");
            $stmt->bind_param("sssii", $hari, $jam_mulai, $jam_selesai, $id, $dokter_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header('Location: jadwal.php');
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM jadwal_dokter WHERE id = ? AND dokter_id = ?");
    $stmt->bind_param("ii", $id, $dokter_id);
    $stmt->execute();
    $stmt->close();
    header('Location: jadwal.php');
    exit;
}

$query = "SELECT id, hari, jam_mulai, jam_selesai FROM jadwal_dokter WHERE dokter_id = ? ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $dokter_id);
$stmt->execute();
$result = $stmt->get_result();
$jadwals = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jadwal Saya - Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white font-modify flex min-h-screen">
    <?php include '../components/sidebar.php'; ?>

    <main class="flex-1 ml-64 p-8">
        <h1 class="text-3xl font-bold mb-10 text-purple-600">Jadwal Praktek Saya</h1>

        <!-- Form Tambah Jadwal -->
        <section class="mb-12 w-full max-w-md bg-white p-6 rounded-lg shadow-lg border border-purple-200">
            <h2 class="text-xl font-semibold mb-6 text-purple-700">Tambah Jadwal Baru</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="tambah" />
                <label class="block mb-2 font-medium text-gray-800">Hari</label>
                <select name="hari" required
                    class="w-full p-3 border border-purple-300 rounded-md focus:ring-purple-500">
                    <option value="">Pilih Hari</option>
                    <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day): ?>
                    <option value="<?= $day ?>"><?= $day ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mb-2 font-medium text-gray-800">Jam Mulai</label>
                <input type="time" name="jam_mulai" required
                    class="w-full p-3 border border-purple-300 rounded-md focus:ring-purple-500" />

                <label class="block mb-2 font-medium text-gray-800">Jam Selesai</label>
                <input type="time" name="jam_selesai" required
                    class="w-full p-3 border border-purple-300 rounded-md focus:ring-purple-500" />

                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-2 rounded-md">
                    Simpan
                </button>
            </form>
        </section>

        <!-- Tabel Jadwal -->
        <section class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg border border-purple-200">
            <h2 class="text-xl font-semibold mb-6 text-purple-700">Jadwal Praktek Anda</h2>

            <?php if (count($jadwals) === 0): ?>
            <p class="text-gray-700">Belum ada jadwal yang dibuat.</p>
            <?php else: ?>
            <table class="w-full border-collapse border border-purple-300 rounded-md">
                <thead>
                    <tr class="bg-purple-600 text-white">
                        <th class="px-4 py-3">Hari</th>
                        <th class="px-4 py-3">Jam Mulai</th>
                        <th class="px-4 py-3">Jam Selesai</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jadwals as $j): ?>
                    <tr class="border-t border-purple-200 hover:bg-purple-50">
                        <td class="px-4 py-3"><?= htmlspecialchars($j['hari']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($j['jam_mulai']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($j['jam_selesai']) ?></td>
                        <td class="px-4 py-3">
                            <button type="button" class="text-purple-700 font-semibold hover:underline editBtn"
                                data-id="<?= $j['id'] ?>" data-hari="<?= $j['hari'] ?>"
                                data-jam_mulai="<?= $j['jam_mulai'] ?>" data-jam_selesai="<?= $j['jam_selesai'] ?>">
                                Edit
                            </button>
                            <span class="text-gray-500 mx-1">|</span>
                            <a href="?hapus=<?= $j['id'] ?>" class="text-red-600 hover:underline"
                                onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </section>
    </main>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
            <h2 class="text-xl font-semibold mb-4 text-purple-700">Edit Jadwal</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="id" id="editId" />
                <label>Hari</label>
                <select name="hari" id="editHari" required class="w-full p-3 border border-purple-300 rounded-md">
                    <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day): ?>
                    <option value="<?= $day ?>"><?= $day ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" id="editJamMulai" required
                    class="w-full p-3 border border-purple-300 rounded-md" />
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" id="editJamSelesai" required
                    class="w-full p-3 border border-purple-300 rounded-md" />
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal(false)"
                        class="px-4 py-2 rounded-md border border-gray-400 text-gray-700 hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const modal = document.getElementById('editModal');
    const editButtons = document.querySelectorAll('.editBtn');

    function toggleModal(show = true) {
        modal.classList.toggle('hidden', !show);
    }

    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editId').value = btn.dataset.id;
            document.getElementById('editHari').value = btn.dataset.hari;
            document.getElementById('editJamMulai').value = btn.dataset.jam_mulai;
            document.getElementById('editJamSelesai').value = btn.dataset.jam_selesai;
            toggleModal(true);
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) toggleModal(false);
    });
    </script>
</body>

</html>