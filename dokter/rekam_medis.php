<?php
session_start();
require '../config/koneksi.php';

// Pastikan dokter sudah login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dokter') {
    header('Location: ../auth/login.php');
    exit;
}

$dokter_id = $_SESSION['user']['id'];

// Ambil pasien_id dari query string
$pasien_id = isset($_GET['pasien_id']) ? intval($_GET['pasien_id']) : 0;

// Ambil data pendaftaran yang valid untuk dokter dan pasien ini (status diterima)
$stmt = $conn->prepare("
    SELECT p.*, 
           u_pasien.id AS pasien_id, u_pasien.username AS nama_pasien,
           u_dokter.id AS dokter_id, u_dokter.username AS nama_dokter
    FROM pendaftaran p
    JOIN users u_pasien ON p.pasien_id = u_pasien.id AND u_pasien.role = 'pasien'
    JOIN users u_dokter ON p.dokter_id = u_dokter.id AND u_dokter.role = 'dokter'
    WHERE p.dokter_id = ? AND p.pasien_id = ? AND p.status = 'diterima'
    ORDER BY p.tanggal DESC
    LIMIT 1
");
$stmt->bind_param("ii", $dokter_id, $pasien_id);
$stmt->execute();
$pendaftaran = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pendaftaran) {
    die("Data pendaftaran tidak ditemukan atau belum disetujui.");
}

// Handle form submit untuk simpan rekam medis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosa = $_POST['diagnosa'] ?? '';
    $tindakan = $_POST['tindakan'] ?? '';
    $resep = $_POST['resep'] ?? '';

    if (!$diagnosa || !$tindakan) {
        $error = "Diagnosa dan tindakan wajib diisi.";
    } else {
      $stmt = $conn->prepare("INSERT INTO rekam_medis (pendaftaran_id, dokter_id, pasien_id, diagnosa, tindakan, resep) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiisss", $pendaftaran_id, $dokter_id, $pasien_id, $diagnosa, $tindakan, $resep);

        $stmt->execute();
        $stmt->close();

        header("Location: konsultasi.php?message=rekam_medis_berhasil");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Isi Rekam Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-50 min-h-screen flex font-sans text-gray-800 font-modify">

    <aside class="w-64 bg-white shadow-md min-h-screen sticky top-0 border-r border-purple-200">
        <?php include '../components/sidebar.php'; ?>
    </aside>

    <main class="flex-1 p-10 overflow-auto">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-10">
            <h1
                class="text-4xl font-extrabold mb-8 text-purple-700 border-b border-purple-300 pb-3 flex items-center gap-3">
                <i class="fas fa-notes-medical"></i> Isi Rekam Medis Pasien
            </h1>

            <div
                class="mb-8 bg-purple-50 border border-purple-200 p-5 rounded-lg text-purple-900 space-y-1 font-medium">
                <p><strong>Pasien:</strong> <?= htmlspecialchars($pendaftaran['nama_pasien']) ?></p>
                <p><strong>Tanggal Konsultasi:</strong> <?= htmlspecialchars($pendaftaran['tanggal']) ?></p>
                <p><strong>Keluhan:</strong> <?= htmlspecialchars($pendaftaran['keluhan']) ?></p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg shadow-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-8">
                <div>
                    <label for="diagnosa" class="block font-semibold text-purple-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-stethoscope"></i> Diagnosa <span class="text-red-500">*</span>
                    </label>
                    <textarea id="diagnosa" name="diagnosa" required
                        class="w-full border border-purple-300 bg-purple-50 focus:bg-white focus:border-purple-500 rounded-3xl px-6 py-4 resize-y shadow-md transition"
                        rows="5"><?= $_POST['diagnosa'] ?? '' ?></textarea>
                </div>

                <div>
                    <label for="tindakan" class="block font-semibold text-purple-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-hand-holding-medical"></i> Tindakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="tindakan" name="tindakan" required
                        class="w-full border border-purple-300 bg-purple-50 focus:bg-white focus:border-purple-500 rounded-3xl px-6 py-4 resize-y shadow-md transition"
                        rows="5"><?= $_POST['tindakan'] ?? '' ?></textarea>
                </div>

                <div>
                    <label for="resep" class="block font-semibold text-purple-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-prescription-bottle-alt"></i> Resep (Opsional)
                    </label>
                    <textarea id="resep" name="resep"
                        class="w-full border border-purple-200 bg-purple-50 focus:bg-white focus:border-purple-400 rounded-3xl px-6 py-3 resize-y shadow-md transition"
                        rows="4"><?= $_POST['resep'] ?? '' ?></textarea>
                </div>

                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-3xl font-semibold shadow-lg transition transform hover:scale-105 flex items-center gap-3 justify-center">
                    <i class="fas fa-floppy-disk"></i> Simpan Rekam Medis
                </button>
            </form>
        </div>
    </main>

</body>

</html>