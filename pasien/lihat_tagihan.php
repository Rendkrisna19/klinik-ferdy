<?php
session_start();
include_once '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pasien') {
    header('Location: ../auth/login.php');
    exit;
}

$pasien_id = $_SESSION['user']['id'];
$errors = [];
$success = '';

// Handle upload bukti transfer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_bukti'])) {
    $tagihan_id = $_POST['tagihan_id'];
    if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Gagal upload file bukti transfer.';
    } else {
        $file = $_FILES['bukti'];
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Format file tidak diperbolehkan. Gunakan jpg, jpeg, png, gif.';
        } else {
            $folder = '../uploads/bukti_transfer/';
            if (!is_dir($folder)) mkdir($folder, 0755, true);

            $newName = 'bukti_'.$tagihan_id.'_'.time().'.'.$ext;
            $targetPath = $folder . $newName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $tagihan_id = mysqli_real_escape_string($conn, $tagihan_id);
                $newName = mysqli_real_escape_string($conn, $newName);

                $update = mysqli_query($conn, "UPDATE tagihan_pembayaran SET bukti_transfer='$newName', status='pending' WHERE id='$tagihan_id' AND pasien_id='$pasien_id'");

                if ($update) {
                    $success = 'Bukti transfer berhasil diupload. Silakan tunggu konfirmasi dari admin.';
                } else {
                    $errors[] = 'Gagal menyimpan data bukti transfer.';
                    unlink($targetPath); // hapus file jika gagal simpan DB
                }
            } else {
                $errors[] = 'Gagal memindahkan file upload.';
            }
        }
    }
}

// Ambil data tagihan pasien
$query = mysqli_query($conn, "SELECT * FROM tagihan_pembayaran WHERE pasien_id = '$pasien_id' ORDER BY created_at DESC");
?>

<body class="bg-purple-50 flex min-h-screen font-modify">

    <?php include '../components/sidebar.php'; ?>

    <main
        class="flex-1 ml-64 p-8 bg-white rounded-l-3xl shadow-lg min-h-screen font-sans font-modify max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-purple-700">Tagihan Pembayaran Saya</h1>

        <?php if ($errors): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-300 shadow-sm">
            <?= implode('<br>', $errors) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-purple-100 text-purple-700 p-4 rounded-lg mb-6 border border-purple-300 shadow-sm">
            <?= $success ?>
        </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($query) === 0): ?>
        <p class="text-gray-500 italic">Tidak ada tagihan pembayaran.</p>
        <?php else: ?>
        <div class="overflow-x-auto rounded-lg shadow-inner border border-purple-200">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left border border-purple-800">Tanggal</th>
                        <th class="px-6 py-3 text-right border border-purple-800">Nominal (Rp)</th>
                        <th class="px-6 py-3 text-center border border-purple-800">Status</th>
                        <th class="px-6 py-3 text-center border border-purple-800">Bukti Transfer</th>
                        <th class="px-6 py-3 text-center border border-purple-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr class="border-t border-purple-200 hover:bg-purple-50 transition-colors">
                        <td class="px-6 py-3 border text-purple-800">
                            <?= date('d M Y', strtotime($row['created_at'])) ?>
                        </td>
                        <td class="px-6 py-3 text-right border text-purple-900 font-semibold">
                            <?= number_format($row['nominal'], 2, ',', '.') ?>
                        </td>
                        <td class="px-6 py-3 text-center border">
                            <?php if ($row['status'] === 'lunas'): ?>
                            <span class="text-green-600 font-semibold">Lunas</span>
                            <?php else: ?>
                            <span class="text-red-600 font-semibold">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3 text-center border">
                            <?php if ($row['bukti_transfer']): ?>
                            <a href="../uploads/bukti_transfer/<?= htmlspecialchars($row['bukti_transfer']) ?>"
                                target="_blank" class="text-purple-700 underline hover:text-purple-900 transition">Lihat
                                Bukti</a>
                            <?php else: ?>
                            <span class="text-gray-400 italic">Belum upload</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3 text-center border">
                            <?php if ($row['status'] !== 'lunas'): ?>
                            <form method="POST" enctype="multipart/form-data" class="space-y-2">
                                <input type="hidden" name="tagihan_id" value="<?= $row['id'] ?>">
                                <input type="file" name="bukti" accept="image/*" required
                                    class="block w-full text-sm text-purple-800 border border-purple-300 rounded px-3 py-1" />
                                <button type="submit" name="upload_bukti"
                                    class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded transition text-sm w-full">
                                    Upload Bukti
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-green-600 font-medium">Sudah lunas</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </main>

    <style>
    body {
        display: flex;
        min-height: 100vh;
        background-color: #f5f3ff;
        /* Tailwind purple-50 */
    }
    </style>