<?php
require_once '../vendor/autoload.php'; // Path ke autoload Dompdf, sesuaikan

use Dompdf\Dompdf;

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'resepsionis') {
    header('Location: /auth/login.php');
    exit;
}

include_once '../config/koneksi.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    die('ID tidak valid');
}

$query = mysqli_query($conn, "SELECT p.*, u1.username AS pasien, u2.username AS dokter 
                              FROM pendaftaran p 
                              JOIN users u1 ON p.pasien_id = u1.id 
                              JOIN users u2 ON p.dokter_id = u2.id 
                              WHERE p.id = '$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    die('Data tidak ditemukan');
}

// Buat HTML untuk PDF
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Kartu Antrian</title>
    <style>
        body { font-family: "Poppins", sans-serif; background: #f3e8ff; color: #5b21b6; text-align: center; }
        .container { border: 2px solid #a78bfa; background: white; border-radius: 20px; padding: 30px; max-width: 400px; margin: auto; }
        h2 { font-size: 28px; margin-bottom: 20px; }
        p { font-size: 18px; margin: 8px 0; }
        .queue-number { font-size: 24px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kartu Antrian</h2>
        <p><strong>Nama Pasien:</strong> ' . htmlspecialchars($data['pasien']) . '</p>
        <p><strong>Dokter:</strong> ' . htmlspecialchars($data['dokter']) . '</p>
        <p><strong>Tanggal:</strong> ' . htmlspecialchars($data['tanggal']) . '</p>
        <p><strong>Keluhan:</strong> ' . nl2br(htmlspecialchars($data['keluhan'])) . '</p>
        <p class="queue-number">Nomor Antrian: #' . htmlspecialchars($data['id']) . '</p>
    </div>
</body>
</html>
';

// Buat instance Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait'); // Ukuran kartu antrian kecil
$dompdf->render();

// Output file PDF ke browser
$dompdf->stream("kartu_antrian_{$data['id']}.pdf", ["Attachment" => false]);
exit;