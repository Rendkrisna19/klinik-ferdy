<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dokter') {
    header('Location: ../auth/login.php');
    exit;
}

$dokter_id = $_SESSION['user']['id'];

$errors = [];
$success = null;

// Proses update rekam medis via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);
    $resep = trim($_POST['resep']);

    if (!$diagnosa) $errors[] = "Diagnosa tidak boleh kosong.";
    if (!$tindakan) $errors[] = "Tindakan tidak boleh kosong.";

    if (!$errors) {
        $stmtUpdate = $conn->prepare("UPDATE rekam_medis SET diagnosa = ?, tindakan = ?, resep = ?, updated_at = NOW() WHERE id = ? AND dokter_id = ?");
        $stmtUpdate->bind_param("sssii", $diagnosa, $tindakan, $resep, $edit_id, $dokter_id);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows > 0) {
            $success = "Rekam medis berhasil diperbarui.";
        } else {
            $errors[] = "Gagal memperbarui rekam medis atau tidak ada perubahan.";
        }
    }
}

// Hapus rekam medis
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmtDel = $conn->prepare("DELETE FROM rekam_medis WHERE id = ? AND dokter_id = ?");
    $stmtDel->bind_param("ii", $delete_id, $dokter_id);
    $stmtDel->execute();
    header('Location: riwayat_rekammedis.php');
    exit;
}

// Ambil data rekam medis dokter
$query = "
    SELECT 
        rm.id,
        rm.created_at,
        rm.diagnosa,
        rm.tindakan,
        rm.resep,
        u.username AS nama_pasien
    FROM rekam_medis rm
    JOIN users u ON rm.pasien_id = u.id
    WHERE rm.dokter_id = ?
    ORDER BY rm.created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $dokter_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dokter - History Rekam Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

</head>

<body class="bg-white font-modify min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md min-h-screen sticky top-0">
        <?php include '../components/sidebar.php'; ?>
    </aside>

    <!-- Konten utama -->
    <main class="flex-1 p-8 overflow-auto bg-white rounded-l-lg">
        <h1 class="text-3xl font-bold mb-6 text-purple-700"><i class="fas fa-notes-medical mr-2"></i>History Rekam Medis
        </h1>

        <?php if ($errors): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php elseif ($success): ?>
        <div class="mb-4 p-4 bg-purple-100 text-purple-700 rounded">
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <?php if ($result->num_rows === 0): ?>
        <p class="text-gray-700">Belum ada rekam medis yang dibuat.</p>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 shadow hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-purple-800 font-bold text-lg">
                        <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
                        <?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?>
                    </div>
                    <div class="text-sm bg-purple-100 text-purple-600 px-3 py-1 rounded-full">
                        <i class="fas fa-user-injured mr-1"></i><?= htmlspecialchars($row['nama_pasien']) ?>
                    </div>
                </div>

                <div class="mb-2">
                    <h3 class="text-md font-semibold text-purple-700 mb-1"><i
                            class="fas fa-stethoscope mr-2"></i>Diagnosa</h3>
                    <p class="text-gray-800 whitespace-pre-line"><?= nl2br(htmlspecialchars($row['diagnosa'])) ?></p>
                </div>

                <div class="mb-2">
                    <h3 class="text-md font-semibold text-purple-700 mb-1"><i
                            class="fas fa-hand-holding-medical mr-2"></i>Tindakan</h3>
                    <p class="text-gray-800 whitespace-pre-line"><?= nl2br(htmlspecialchars($row['tindakan'])) ?></p>
                </div>

                <div class="mb-4">
                    <h3 class="text-md font-semibold text-purple-700 mb-1"><i class="fas fa-pills mr-2"></i>Resep</h3>
                    <p class="text-gray-800 whitespace-pre-line">
                        <?= $row['resep'] ? nl2br(htmlspecialchars($row['resep'])) : '-' ?></p>
                </div>

                <div class="flex justify-end space-x-4">
                    <button onclick='openModal(<?= htmlspecialchars(json_encode($row)) ?>)'
                        class="text-yellow-600 hover:text-yellow-700 font-medium text-sm flex items-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="confirmDelete(<?= $row['id'] ?>)"
                        class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </main>


    <!-- Modal Edit -->
    <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative border border-purple-200">
            <button onclick="closeModal()"
                class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 font-bold text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-4 text-purple-700">Edit Rekam Medis</h2>

            <form method="post" id="formEdit">
                <input type="hidden" name="edit_id" id="edit_id" />

                <label class="block mb-2 font-semibold text-gray-700">Diagnosa:</label>
                <textarea name="diagnosa" id="diagnosa" rows="4" class="w-full p-2 border border-purple-300 rounded"
                    required></textarea>

                <label class="block mt-4 mb-2 font-semibold text-gray-700">Tindakan:</label>
                <textarea name="tindakan" id="tindakan" rows="4" class="w-full p-2 border border-purple-300 rounded"
                    required></textarea>

                <label class="block mt-4 mb-2 font-semibold text-gray-700">Resep (opsional):</label>
                <textarea name="resep" id="resep" rows="3"
                    class="w-full p-2 border border-purple-300 rounded"></textarea>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit"
                        class="px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">Simpan</button>
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2 border border-purple-400 rounded hover:bg-purple-100 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>


    <script>
    function openModal(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('diagnosa').value = data.diagnosa;
        document.getElementById('tindakan').value = data.tindakan;
        document.getElementById('resep').value = data.resep || '';
        document.getElementById('modalEdit').classList.remove('hidden');
        document.getElementById('modalEdit').classList.add('flex');
        window.scrollTo(0, 0);
    }

    function closeModal() {
        document.getElementById('modalEdit').classList.add('hidden');
        document.getElementById('modalEdit').classList.remove('flex');
    }

    function confirmDelete(id) {
        if (confirm("Yakin ingin menghapus rekam medis ini?")) {
            window.location.href = "?delete_id=" + id;
        }
    }
    </script>

</body>

</html>