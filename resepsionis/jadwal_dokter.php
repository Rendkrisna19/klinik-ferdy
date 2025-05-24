<?php
session_start();
include_once '../config/koneksi.php';

// Cek login dan role pasien
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'resepsionis') {
    header("Location: /auth/login.php");
    exit;
}

// Ambil data jadwal dokter
$result = $conn->query("SELECT jd.*, u.username AS nama_dokter FROM jadwal_dokter jd 
                        JOIN users u ON jd.dokter_id = u.id 
                        ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Jadwal Dokter - Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primaryPurple: '#7c3aed',
                    primaryLight: '#FFFFFFFF',
                    borderPurple: '#a78bfa',
                    textPurple: '#5b21b6',
                },
                fontFamily: {
                    modify: ['Poppins', 'sans-serif'],
                }
            }
        }
    }
    </script>
</head>

<body class="bg-primaryLight min-h-screen font-modify">

    <?php include_once '../components/sidebar.php'; ?>

    <main class="ml-64 p-6">
        <h1 class="text-3xl font-bold text-primaryPurple mb-6">Jadwal Dokter</h1>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div
                class="bg-white rounded-xl shadow p-6 border-b-4 border-primaryPurple hover:shadow-lg transition cursor-pointer">
                <div class="mb-3 text-primaryPurple font-semibold text-lg"><?= htmlspecialchars($row['nama_dokter']) ?>
                </div>
                <div class="text-textPurple">
                    <p><strong>Hari:</strong> <?= htmlspecialchars($row['hari']) ?></p>
                    <p><strong>Jam:</strong> <?= substr($row['jam_mulai'], 0, 5) ?> -
                        <?= substr($row['jam_selesai'], 0, 5) ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

</body>

</html>