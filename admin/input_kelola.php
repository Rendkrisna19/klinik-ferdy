<?php
require '../config/koneksi.php';

// Create
if (isset($_POST['create'])) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt->bind_param("ssss", $_POST['username'], $_POST['email'], $hash, $_POST['role']);
    $stmt->execute();
    header("Location: crud.php");
    exit;
}

// Edit
if (isset($_POST['edit'])) {
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $_POST['username'], $_POST['email'], $_POST['role'], $_POST['id']);
    $stmt->execute();
    header("Location: input_kelola.php");
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: input_kelola.php");
    exit;
}

// Fetch all data
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
    }
    </script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans text-gray-800 font-modify">
    <?php include '../components/sidebar.php'; ?>

    <div class="ml-64 p-6">
        <h1 class="text-3xl font-bold text-purple-600 mb-6">Kelola Akun Klinik</h1>

        <!-- Form Tambah Akun -->
        <form method="post"
            class="bg-white p-6 rounded-xl shadow-md w-full max-w-md mb-10 space-y-4 border border-purple-200">
            <input name="username" placeholder="Username" required class="w-full border rounded-lg px-4 py-2" />
            <input name="email" type="email" placeholder="Email" required class="w-full border rounded-lg px-4 py-2" />
            <input name="password" type="password" placeholder="Password" required
                class="w-full border rounded-lg px-4 py-2" />
            <select name="role" required class="w-full border rounded-lg px-4 py-2">
                <option value="">Pilih Role</option>
                <option>admin</option>
                <option>dokter</option>
                <option>resepsionis</option>
                <option>pasien</option>
            </select>
            <button name="create"
                class="w-full bg-purple-600 text-white rounded-lg px-4 py-2 hover:bg-purple-700 transition flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Akun
            </button>
        </form>

        <!-- Tabel Akun -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-xl shadow-sm">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($d = $result->fetch_assoc()): ?>
                    <tr class="border-t hover:bg-gray-100">
                        <td class="px-4 py-3"><?= htmlspecialchars($d['username']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($d['email']) ?></td>
                        <td class="px-4 py-3 capitalize"><?= htmlspecialchars($d['role']) ?></td>
                        <td class="px-4 py-3 flex justify-center items-center gap-3">
                            <button onclick="openModal('<?= $d['id'] ?>')" title="Edit">
                                <i class="fas fa-pen text-purple-600 hover:text-purple-800 text-lg"></i>
                            </button>
                            <a href="?delete=<?= $d['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')"
                                title="Hapus">
                                <i class="fas fa-trash text-red-500 hover:text-red-700 text-lg"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div id="modal-<?= $d['id'] ?>"
                        class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="bg-white p-6 rounded-xl w-96 shadow-lg border border-gray-300">
                            <h2 class="text-xl font-bold text-purple-600 mb-4">Edit Akun</h2>
                            <form method="post" class="space-y-3">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <input name="username" value="<?= $d['username'] ?>" required
                                    class="w-full border px-3 py-2 rounded-lg" />
                                <input name="email" type="email" value="<?= $d['email'] ?>" required
                                    class="w-full border px-3 py-2 rounded-lg" />
                                <select name="role" class="w-full border px-3 py-2 rounded-lg">
                                    <option <?= $d['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
                                    <option <?= $d['role'] === 'dokter' ? 'selected' : '' ?>>dokter</option>
                                    <option <?= $d['role'] === 'resepsionis' ? 'selected' : '' ?>>resepsionis</option>
                                    <option <?= $d['role'] === 'pasien' ? 'selected' : '' ?>>pasien</option>
                                </select>
                                <div class="flex justify-end gap-3 mt-4">
                                    <button type="button" onclick="closeModal('<?= $d['id'] ?>')"
                                        class="bg-gray-300 px-4 py-2 rounded-lg flex items-center gap-2">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                    <button name="edit"
                                        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center gap-2">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>