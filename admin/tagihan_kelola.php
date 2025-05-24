<?php
session_start();
include_once '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Handle update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $tagihan_id = $_POST['tagihan_id'];
    $status = $_POST['status'];

    if (in_array($status, ['pending', 'lunas'])) {
        $tagihan_id = mysqli_real_escape_string($conn, $tagihan_id);
        $status = mysqli_real_escape_string($conn, $status);

        mysqli_query($conn, "UPDATE tagihan_pembayaran SET status='$status' WHERE id='$tagihan_id'");
        $success = 'Status pembayaran berhasil diupdate.';
    }
}

// Ambil data tagihan dengan nama pasien dari tabel users
$query = mysqli_query($conn, "SELECT tp.*, u.username AS pasien_nama FROM tagihan_pembayaran tp JOIN users u ON tp.pasien_id = u.id ORDER BY tp.created_at DESC");
?>

<body class="bg-white flex min-h-screen font-modify">

    <?php include '../components/sidebar.php'; ?>

    <main class="flex-1 ml-64 p-6 bg-gray-50 rounded-l-lg shadow-md min-h-screen font-modify">
        <h1 class="text-3xl font-bold mb-6 text-purple-700">Kelola Tagihan Pembayaran</h1>

        <?php if (!empty($success)): ?>
        <div class="bg-purple-100 text-purple-800 p-3 rounded mb-4 border border-purple-300">
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($query) === 0): ?>
        <p class="text-gray-600">Belum ada tagihan pembayaran.</p>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded shadow-md text-sm">
                <thead class="bg-purple-100 text-purple-800 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="border px-4 py-2 text-left">Pasien</th>
                        <th class="border px-4 py-2 text-left">Tanggal</th>
                        <th class="border px-4 py-2 text-right">Nominal (Rp)</th>
                        <th class="border px-4 py-2 text-center">Status</th>
                        <th class="border px-4 py-2 text-center">Bukti Transfer</th>
                        <th class="border px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr class="hover:bg-purple-50 transition">
                        <td class="border px-4 py-2"><?= htmlspecialchars($row['pasien_nama']) ?></td>
                        <td class="border px-4 py-2"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td class="border px-4 py-2 text-right"><?= number_format($row['nominal'], 2, ',', '.') ?></td>
                        <td class="border px-4 py-2 text-center">
                            <?php if ($row['status'] === 'lunas'): ?>
                            <span class="inline-flex items-center text-green-600 font-semibold">
                                ✅ <span class="ml-1">Lunas</span>
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center text-red-600 font-semibold">
                                ⏳ <span class="ml-1">Pending</span>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <?php if ($row['bukti_transfer']): ?>
                            <a href="../uploads/bukti_transfer/<?= htmlspecialchars($row['bukti_transfer']) ?>"
                                target="_blank" class="text-purple-600 underline hover:text-purple-800">
                                📄 Lihat Bukti
                            </a>
                            <?php else: ?>
                            <span class="text-gray-400 italic">Belum upload</span>
                            <?php endif; ?>
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <?php if ($row['bukti_transfer']): ?>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="tagihan_id" value="<?= $row['id'] ?>">
                                <select name="status"
                                    class="border border-purple-300 rounded px-2 py-1 text-sm focus:ring-purple-500"
                                    required>
                                    <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>
                                        Pending
                                    </option>
                                    <option value="lunas" <?= $row['status'] === 'lunas' ? 'selected' : '' ?>>Lunas
                                    </option>
                                </select>
                                <button type="submit" name="update_status"
                                    class="ml-2 bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 transition text-sm shadow">
                                    Update
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-gray-400 italic">Tunggu upload bukti</span>
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
        background-color: #f3f4f6;
        /* gray-100 */
    }
    </style>