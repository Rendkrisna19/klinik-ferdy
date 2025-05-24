<?php
session_start();
require '../config/koneksi.php';

// Pastikan pasien sudah login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pasien') {
    header('Location: ../auth/login.php');
    exit;
}

$pasien_id = $_SESSION['user']['id'];

// Ambil rekam medis pasien dengan nama dokter dari tabel users
$query = "
    SELECT 
        rm.id,
        rm.created_at,
        rm.diagnosa,
        rm.tindakan,
        rm.resep,
        u.username AS nama_dokter
    FROM rekam_medis rm
    JOIN users u ON rm.dokter_id = u.id
    WHERE rm.pasien_id = ?
    ORDER BY rm.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pasien_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pasien - Rekam Medis Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    modify: ['Inter', 'sans-serif'],
                },
                colors: {
                    primary: '#800D94FF', // teal-600
                    primaryLight: '#DE5EEAFF', // teal-300
                    tableHeaderBg: '#F3CCFBFF', // teal-100
                    tableRowHover: '#FBE6FFFF', // teal-50
                }
            }
        }
    };
    </script>
</head>

<body class="bg-gray-50 min-h-screen flex font-modify">
    <!-- Sidebar -->
    <aside class="w-72 bg-white shadow-lg min-h-screen sticky top-0 flex flex-col">
        <?php include '../components/sidebar.php'; ?>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-10 overflow-auto bg-white rounded-tl-3xl rounded-bl-3xl shadow-xl">
        <!-- Judul -->
        <div class="flex items-center gap-4 mb-8 border-b border-gray-200 pb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-primary" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 7v10a1 1 0 001 1h4m10-11h2a1 1 0 011 1v10a1 1 0 01-1 1h-2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h1 class="text-4xl font-extrabold text-primary tracking-wide">Rekam Medis Saya</h1>
        </div>

        <!-- Tabel Rekam Medis -->
        <?php if ($result->num_rows === 0): ?>
        <p class="text-gray-600 purple text-lg">Belum ada rekam medis yang tersedia.</p>
        <?php else: ?>
        <div class="overflow-x-auto rounded-lg border border-gray-300 shadow-sm">
            <table class="min-w-full text-left text-sm font-medium">
                <thead class="bg-tableHeaderBg text-primary">
                    <tr>
                        <th class="px-6 py-3 border-r border-gray-300">Tanggal</th>
                        <th class="px-6 py-3 border-r border-gray-300">Dokter</th>
                        <th class="px-6 py-3 border-r border-gray-300">Diagnosa</th>
                        <th class="px-6 py-3 border-r border-gray-300">Tindakan</th>
                        <th class="px-6 py-3">Resep</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-200 hover:bg-tableRowHover transition-colors duration-200">
                        <td class="px-6 py-4 border-r border-gray-200 whitespace-nowrap">
                            <?= htmlspecialchars(date('d M Y', strtotime($row['created_at']))) ?>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200 whitespace-nowrap">
                            <?= htmlspecialchars($row['nama_dokter']) ?>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <?= nl2br(htmlspecialchars($row['diagnosa'])) ?>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <?= nl2br(htmlspecialchars($row['tindakan'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $row['resep'] ? nl2br(htmlspecialchars($row['resep'])) : '-' ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </main>
</body>

</html>