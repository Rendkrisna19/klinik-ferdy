<?php
session_start();
require '../config/koneksi.php'; // koneksi ke database

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        $error = "Email tidak valid.";
    } else {
        // Cari user berdasarkan email dari database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Verifikasi password hashed
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'       => $user['id'],      // simpan id user
                    'email'    => $user['email'],
                    'role'     => $user['role'],
                    'username' => $user['username'],
                ];

                // Redirect sesuai role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: ../admin/index.php");
                        exit();
                    case 'dokter':
                        header("Location: ../dokter/index.php");
                        exit();
                    case 'resepsionis':
                        header("Location: ../resepsionis/index.php");
                        exit();
                    case 'pasien':
                        header("Location: ../pasien/index.php");
                        exit();
                    default:
                        $error = "Role tidak dikenali.";
                }
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Akun tidak ditemukan.";
        }
        $stmt->close();
    }
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Login Klinik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .font-modify {
        font-family: "Oswald", sans-serif;
    }
    </style>
</head>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap');

.font-modify {
    font-family: "Lexend Deca", sans-serif;
}
</style>

<body class="bg-purple-50 min-h-screen flex items-center justify-center font-modify font-modify">

    <div
        class="bg-white shadow-xl border border-purple-100 rounded-xl overflow-hidden w-full max-w-4xl flex flex-col md:flex-row">

        <!-- Gambar Kiri -->
        <div class="md:w-1/2 hidden md:block">
            <img src="https://static.honestdocs.id/system/blog_articles/main_hero_images/000/005/310/original/iStock-913714110_%281%29.jpg"
                alt="Klinik" class="h-full w-full object-cover" />
        </div>

        <!-- Form Login Kanan -->
        <div class="md:w-1/2 w-full p-8">
            <h2 class="text-3xl font-bold text-purple-700 mb-6 text-center">Login Klinik</h2>

            <?php if (isset($error) && $error): ?>
            <p class="text-red-500 text-sm mb-4 text-center"><?= $error ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input name="email" id="email" type="email" placeholder="Email" required
                    class="w-full mb-4 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400" />

                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input name="password" id="password" type="password" placeholder="Password" required
                    class="w-full mb-4 p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400" />

                <button type="submit" name="login"
                    class="bg-purple-600 hover:bg-purple-700 w-full text-white p-3 rounded-lg transition shadow">
                    Login
                </button>
            </form>

            <p class="text-center mt-4 text-sm">
                <a href="register.php" class="text-purple-600 hover:underline">Belum punya akun? Daftar</a>
            </p>
        </div>

    </div>

</body>

</html>