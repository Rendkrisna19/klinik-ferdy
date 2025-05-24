<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'resepsionis') {
    header('Location: /auth/login.php');
    exit;
}

include_once '../config/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT p.*, u1.username AS pasien, u2.username AS dokter 
                              FROM pendaftaran p 
                              JOIN users u1 ON p.pasien_id = u1.id 
                              JOIN users u2 ON p.dokter_id = u2.id 
                              WHERE p.id = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Kartu Antrian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primaryPurple: '#7c3aed', // violet-600
                    primaryLight: '#c4b5fd', // violet-300
                    borderPurple: '#a78bfa' // violet-400
                },
                fontFamily: {
                    modify: ['Poppins', 'sans-serif'],
                }
            }
        }
    }
    </script>
</head>

<body class="bg-primaryLight flex items-center justify-center min-h-screen font-modify p-6">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border-2 border-borderPurple p-8 text-center">
        <h2 class="text-3xl font-extrabold text-primaryPurple mb-6">Kartu Antrian</h2>
        <p class="text-lg mb-2"><strong>Nama Pasien:</strong> <?= htmlspecialchars($data['pasien']) ?></p>
        <p class="text-lg mb-2"><strong>Dokter:</strong> <?= htmlspecialchars($data['dokter']) ?></p>
        <p class="text-lg mb-2"><strong>Tanggal:</strong> <?= htmlspecialchars($data['tanggal']) ?></p>
        <p class="text-lg mb-6"><strong>Keluhan:</strong> <?= nl2br(htmlspecialchars($data['keluhan'])) ?></p>
        <p class="text-2xl font-bold text-primaryPurple">Nomor Antrian: #<?= htmlspecialchars($data['id']) ?></p>

        <div class="mt-8 flex justify-center gap-4">
            <button onclick="window.print()"
                class="bg-primaryPurple hover:bg-primaryPurple/90 text-white font-semibold px-6 py-3 rounded-full transition">
                Cetak Halaman
            </button>

            <form method="POST" action="generate_pdf.php" target="_blank">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <button type="submit"
                    class="bg-purple-700 hover:bg-purple-800 text-white font-semibold px-6 py-3 rounded-full transition">
                    Cetak PDF
                </button>
            </form>
        </div>
    </div>
</body>

</html>