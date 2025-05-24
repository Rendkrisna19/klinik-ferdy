<?php
session_start();
include_once '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit;
}

// Query total pendapatan dari pembayaran dengan status 'lunas'
$result = mysqli_query($conn, "SELECT SUM(nominal) AS total_pendapatan FROM tagihan_pembayaran WHERE status='lunas'");
$row = mysqli_fetch_assoc($result);
$totalPendapatan = $row['total_pendapatan'] ?? 0;

// Query pendapatan bulanan (tahun ini) untuk grafik
$year = date('Y');
$queryPendapatanBulan = mysqli_query($conn,
    "SELECT MONTH(created_at) AS bulan, SUM(nominal) AS total 
     FROM tagihan_pembayaran 
     WHERE status='lunas' AND YEAR(created_at) = '$year' 
     GROUP BY bulan 
     ORDER BY bulan ASC");

// Siapkan data bulan dan total untuk chart
$pendapatanBulan = array_fill(1, 12, 0); // default 0 untuk semua bulan 1-12
while ($data = mysqli_fetch_assoc($queryPendapatanBulan)) {
    $pendapatanBulan[(int)$data['bulan']] = (float)$data['total'];
}

// Buat array label bulan (nama bulan)
$namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-slate-100 font-sans">

    <?php include '../components/sidebar.php'; ?>

    <div class="flex flex-col md:ml-64 p-6 space-y-6">
        <header class="mb-4">
            <h1 class="text-4xl font-bold text-purple-800">Dashboard Admin</h1>
            <p class="text-sm text-gray-500">Selamat datang kembali, admin!</p>
        </header>

        <!-- Baris 1: Total Pendapatan & Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Total Pendapatan -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-purple-600">Total Pendapatan Tahun <?= $year ?></h2>
                <p class="text-4xl font-bold text-purple-800 mt-4">Rp
                    <?= number_format($totalPendapatan, 0, ',', '.') ?>
                </p>
                <span class="text-sm text-gray-500">Data akumulasi dari semua transaksi</span>
            </div>

            <!-- Grafik Pendapatan -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-purple-600 mb-4">Grafik Pendapatan Bulanan</h2>
                <div class="relative h-64">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Menu Lain -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php
      $menus = [
        ['title' => 'Data Pasien', 'icon' => 'fa-user-injured', 'desc' => 'Kelola informasi pasien.', 'link' => 'input_kelola.php'],
        ['title' => 'Jadwal Dokter', 'icon' => 'fa-calendar-check', 'desc' => 'Atur jadwal dokter.', 'link' => 'jadwal_dokter_admin.php'],
        ['title' => 'Pendaftaran', 'icon' => 'fa-notes-medical', 'desc' => 'Data pendaftaran pasien.', 'link' => 'pendaftaran_pasien.php'],
        ['title' => 'Konsultasi', 'icon' => 'fa-stethoscope', 'desc' => 'Pantau sesi konsultasi.', 'link' => 'konsultasi_admin.php'],
        ['title' => 'Rekam Medis', 'icon' => 'fa-file-medical', 'desc' => 'Riwayat rekam medis.', 'link' => 'rekam_medis.php'],
        ['title' => 'Pembayaran', 'icon' => 'fa-credit-card', 'desc' => 'Kelola transaksi pembayaran.', 'link' => 'tagihan_kelola.php'],
      ];

      foreach ($menus as $menu) {
        echo '
          <div class="relative bg-white p-6 rounded-lg shadow hover:shadow-md transition group overflow-hidden">
            <i class="fas '.$menu['icon'].' text-6xl text-pur-100 absolute right-4 top-4 opacity-10 group-hover:opacity-20 transition"></i>
            <h3 class="text-lg font-semibold text-purple-700">'.$menu['title'].'</h3>
            <p class="text-gray-600 text-sm mt-2">'.$menu['desc'].'</p>
            <a href="'.$menu['link'].'" class="inline-block mt-4 text-pur-600 hover:underline text-sm">Kelola</a>
          </div>
        ';
      }
      ?>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    const pendapatanChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($namaBulan) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode(array_values($pendapatanBulan)) ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.6)', // biru dengan opacity
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    </script>

</body>

</html>