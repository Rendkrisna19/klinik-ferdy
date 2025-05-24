<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'resepsionis') {
    header('Location: /auth/login.php');
    exit;
}

include_once '../config/koneksi.php';

// Query semua data pendaftaran dengan join pasien dan dokter
$query = mysqli_query($conn, "SELECT p.id, u1.username AS pasien, u2.username AS dokter, p.tanggal 
                              FROM pendaftaran p 
                              JOIN users u1 ON p.pasien_id = u1.id 
                              JOIN users u2 ON p.dokter_id = u2.id 
                              ORDER BY p.tanggal DESC");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Daftar Pendaftaran Offline</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primaryPurple: '#7c3aed',
                    primaryLight: '#c4b5fd',
                    borderPurple: '#a78bfa'
                },
                fontFamily: {
                    modify: ['Poppins', 'sans-serif'],
                }
            }
        }
    }
    </script>
</head>

<body class="bg-white min-h-screen font-modify">
    <?php include '../components/sidebar.php'; ?>
    <div class="p-6 ml-64">
        <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl border-2 border-borderPurple p-8">
            <h1 class="text-4xl font-extrabold text-primaryPurple mb-8 text-center">Daftar Pendaftaran Offline</h1>

            <table class="min-w-full table-auto border-collapse border border-borderPurple">
                <thead>
                    <tr class="bg-primaryPurple text-white">
                        <th class="border border-borderPurple px-4 py-2">ID</th>
                        <th class="border border-borderPurple px-4 py-2">Pasien</th>
                        <th class="border border-borderPurple px-4 py-2">Dokter</th>
                        <th class="border border-borderPurple px-4 py-2">Tanggal</th>
                        <th class="border border-borderPurple px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr class="odd:bg-white even:bg-primaryLight/50 text-center">
                        <td class="border border-borderPurple px-4 py-2"><?= htmlspecialchars($row['id']) ?></td>
                        <td class="border border-borderPurple px-4 py-2"><?= htmlspecialchars($row['pasien']) ?></td>
                        <td class="border border-borderPurple px-4 py-2"><?= htmlspecialchars($row['dokter']) ?></td>
                        <td class="border border-borderPurple px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td class="border border-borderPurple px-4 py-2">
                            <form method="POST" action="generate_pdf.php" target="_blank" class="inline">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button
                                    class="bg-primaryPurple hover:bg-primaryPurple/90 text-white font-semibold px-4 py-1 rounded transition"
                                    type="submit">
                                    Cetak Kartu PDF
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">Belum ada data pendaftaran.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
</body>

</html>