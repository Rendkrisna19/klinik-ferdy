        <?php
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dokter') {
            header('Location: /auth/login.php');
            exit;
        }

        include_once '../config/koneksi.php';

        $dokter_id = $_SESSION['user']['id'];

        // Ambil jadwal dokter sesuai dokter yang login
        $queryJadwal = "SELECT hari, jam_mulai, jam_selesai FROM jadwal_dokter WHERE dokter_id = ? ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jam_mulai";
        $stmtJadwal = $conn->prepare($queryJadwal);
        $stmtJadwal->bind_param("i", $dokter_id);
        $stmtJadwal->execute();
        $resultJadwal = $stmtJadwal->get_result();
        $jadwals = $resultJadwal->fetch_all(MYSQLI_ASSOC);
        $stmtJadwal->close();

        // Ambil daftar konsultasi pasien untuk dokter ini
        // Ambil daftar konsultasi pasien untuk dokter ini
        $queryKonsul = "
            SELECT p.id, u.username AS nama_pasien, p.tanggal, p.keluhan, p.status, p.created_at
            FROM pendaftaran p
            JOIN users u ON p.pasien_id = u.id AND u.role = 'pasien'
            WHERE p.dokter_id = ?
            ORDER BY p.tanggal DESC, p.created_at DESC
        ";
        $stmtKonsul = $conn->prepare($queryKonsul);
        $stmtKonsul->bind_param("i", $dokter_id);
        $stmtKonsul->execute();
        $resultKonsul = $stmtKonsul->get_result();
        $konsultasis = $resultKonsul->fetch_all(MYSQLI_ASSOC);
        $stmtKonsul->close();


        ?>

        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>Dashboard Dokter - Klinik Sehat</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>

        <body class="bg-white flex min-h-screen font-sans  font-modify">
            <?php include '../components/sidebar.php'; ?>

            <main class="flex-1 ml-64 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-4xl font-bold text-purple-600">Dashboard Dokter</h1>
                    <!-- Gambar profil dokter -->
                    <!-- <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-purple-600 shadow-md">
                        <img src="<?= htmlspecialchars($foto_dokter ?? 'https://png.pngtree.com/png-clipart/20231006/original/pngtree-cartoon-character-doctor-png-image_13129994.png') ?>"
                            alt="Foto Dokter" class="object-cover w-full h-full" />
                    </div> -->
                </div>

                <!-- Jadwal Dokter -->
                <section class="mb-12">
                    <h2 class="text-2xl font-semibold mb-5 text-purple-700">Jadwal Praktek Anda</h2>
                    <?php if (count($jadwals) === 0): ?>
                    <p class="text-gray-700">Jadwal praktek belum tersedia.</p>
                    <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($jadwals as $j): ?>
                        <div class="bg-white border border-purple-200 rounded-lg shadow hover:shadow-md transition p-5">
                            <h3 class="text-lg font-bold text-purple-700 mb-2"><?= htmlspecialchars($j['hari']) ?></h3>
                            <p class="text-gray-700">
                                <span class="font-semibold">Jam Mulai:</span>
                                <?= date('H:i', strtotime($j['jam_mulai'])) ?>
                            </p>
                            <p class="text-gray-700">
                                <span class="font-semibold">Jam Selesai:</span>
                                <?php
                        $jam_mulai = strtotime($j['jam_mulai']);
                        $jam_selesai = strtotime($j['jam_selesai']);
                        if ($jam_selesai <= $jam_mulai) {
                            echo date('H:i', $jam_selesai) . " (+1 hari)";
                        } else {
                            echo date('H:i', $jam_selesai);
                        }
                    ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php endif; ?>
                </section>

                <!-- Daftar Konsultasi Pasien -->
                <section>
                    <h2 class="text-2xl font-semibold mb-5 text-purple-700">Daftar Konsultasi Pasien</h2>

                    <?php if (count($konsultasis) === 0): ?>
                    <p class="text-gray-700">Belum ada konsultasi dari pasien.</p>
                    <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($konsultasis as $k): ?>
                        <div
                            class="relative bg-purple-50 border border-purple-200 rounded-xl shadow-md hover:shadow-lg transition-all p-6">
                            <!-- Icon Pasien -->
                            <div
                                class="absolute -top-6 left-6 w-12 h-12 rounded-full bg-white border-2 border-purple-400 shadow overflow-hidden">
                                <img src="https://cdn-icons-png.flaticon.com/512/706/706830.png" alt="Pasien"
                                    class="w-full h-full object-cover" />
                            </div>

                            <!-- Isi Card -->
                            <div class="mt-6">
                                <h3 class="text-lg font-bold text-purple-700 mb-2">
                                    #<?= htmlspecialchars($k['id']) ?> - <?= htmlspecialchars($k['nama_pasien']) ?>
                                </h3>

                                <p class="text-gray-700 mb-1">
                                    <span class="font-semibold">Tanggal:</span> <?= htmlspecialchars($k['tanggal']) ?>
                                </p>

                                <p class="text-gray-700 mb-2">
                                    <span class="font-semibold">Keluhan:</span>
                                    <br>
                                    <span class="whitespace-pre-line"><?= htmlspecialchars($k['keluhan']) ?></span>
                                </p>

                                <p class="text-gray-700 font-semibold mb-1">
                                    Status: <span class="capitalize"><?= htmlspecialchars($k['status']) ?></span>
                                </p>

                                <p class="text-gray-500 text-sm">
                                    Didaftarkan: <?= date('d M Y H:i', strtotime($k['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </section>


            </main>
        </body>


        </html>