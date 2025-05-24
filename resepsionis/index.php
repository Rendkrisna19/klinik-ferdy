<?php 
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'resepsionis') {
    header('Location: /auth/login.php');
    exit;
}

include_once '../config/koneksi.php';

// Ambil jumlah data dari tabel
$queryPendaftaran = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran");
$totalPendaftaran = mysqli_fetch_assoc($queryPendaftaran)['total'];

$queryKonsultasi = mysqli_query($conn, "SELECT COUNT(*) as total FROM rekam_medis");
$totalKonsultasi = mysqli_fetch_assoc($queryKonsultasi)['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard Resepsionis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primaryPurple: '#7c3aed', // violet-600
                    primaryLight: '#c4b5fd', // violet-300
                    primaryDark: '#5b21b6', // violet-800
                    bgPurpleLight: '#faf5ff', // violet-50
                },
                fontFamily: {
                    modify: ['Inter', 'sans-serif'],
                }
            }
        }
    }
    </script>
</head>

<body class="bg-bgPurpleLight min-h-screen font-modify flex">
    <!-- Sidebar -->
    <?php include '../components/sidebar.php'; ?>

    <main class="flex-1 ml-64 p-10 bg-white rounded-tl-3xl shadow-xl">
        <h1 class="text-4xl font-extrabold text-primaryPurple mb-10">Dashboard Resepsionis</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Kartu info (horizontal) -->
            <div class="flex flex-col sm:flex-row gap-6 lg:flex-col lg:w-1/3">
                <div
                    class="flex items-center bg-white rounded-2xl shadow-lg p-6 border-l-8 border-primaryPurple hover:shadow-xl transition-shadow cursor-pointer">
                    <div
                        class="bg-primaryLight text-primaryDark rounded-full w-16 h-16 flex items-center justify-center mr-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2a4 4 0 014-4h4M7 10v6a4 4 0 004 4h4m-8-2h.01M15 7a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase mb-1">Total Pendaftaran</p>
                        <p class="text-3xl font-semibold text-primaryPurple"><?= $totalPendaftaran ?></p>
                    </div>
                </div>

                <div
                    class="flex items-center bg-white rounded-2xl shadow-lg p-6 border-l-8 border-primaryPurple hover:shadow-xl transition-shadow cursor-pointer">
                    <div
                        class="bg-primaryLight text-primaryDark rounded-full w-16 h-16 flex items-center justify-center mr-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 10h.01M12 14h.01M16 10h.01M9 16h6M4 6h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase mb-1">Total Konsultasi</p>
                        <p class="text-3xl font-semibold text-primaryPurple"><?= $totalKonsultasi ?></p>
                    </div>
                </div>
            </div>

            <!-- Chart di samping kartu -->
            <section
                class="bg-white rounded-3xl shadow-lg p-8 flex-1 flex flex-col justify-between min-h-[320px] max-w-full">
                <h2 class="text-2xl font-semibold text-primaryPurple mb-6">Statistik Pendaftaran & Konsultasi</h2>
                <canvas id="dataChart" class="flex-1"></canvas>
            </section>
        </div>
    </main>

    <script>
    const ctx = document.getElementById('dataChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pendaftaran', 'Konsultasi'],
            datasets: [{
                label: 'Jumlah',
                data: [<?= $totalPendaftaran ?>, <?= $totalKonsultasi ?>],
                backgroundColor: ['#a78bfa', '#7c3aed'],
                borderRadius: 8,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#7c3aed',
                    titleColor: '#fff',
                    bodyColor: '#eee',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#5b21b6',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: '#ede9fe'
                    }
                },
                x: {
                    ticks: {
                        color: '#5b21b6',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    </script>
</body>

</html>