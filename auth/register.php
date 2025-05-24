<?php
include '../config/koneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    // Hash password sebelum disimpan
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    
    $role = 'pasien'; // role default

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registrasi berhasil! Silakan login.');
            window.location = 'login.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Registrasi Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap');

.font-modify {
    font-family: "Lexend Deca", sans-serif;
}
</style>

<body class="min-h-screen bg-purple-50 flex items-center justify-center font-modify">
    <div class="max-w-6xl bg-white rounded-xl shadow-lg flex overflow-hidden w-full mx-4">
        <!-- Kiri: gambar -->
        <div class="hidden md:block md:w-1/2 bg-purple-700 relative">
            <img src="https://cdn-assets-eu.frontify.com/s3/frontify-enterprise-files-eu/eyJwYXRoIjoiaWhoLWhlYWx0aGNhcmUtYmVyaGFkXC9hY2NvdW50c1wvYzNcLzQwMDA2MjRcL3Byb2plY3RzXC8yMDlcL2Fzc2V0c1wvOTNcLzM4MTA2XC8wZjdhMmE0MzQwZjBhNGQ4YmQzYTM4MzQ4OWU1NjNjNi0xNjU4MzAxMDMwLmpwZyJ9:ihh-healthcare-berhad:nYMJB5LuuXfSBJcG-_Q0t1kYGwm69-ssAPGgKRYpLHM?format=webp"
                alt="Dokter Ilustrasi" class="object-cover w-full h-full" />
            <div
                class="absolute inset-0 bg-purple-900 bg-opacity-50 flex flex-col justify-center items-center p-8 text-white">
                <h2 class="text-3xl font-bold mb-2">Selamat Datang di Klinik Kami</h2>
                <p class="text-lg">Registrasi pasien dengan mudah dan cepat untuk pelayanan terbaik.</p>
            </div>
        </div>

        <!-- Kanan: form -->
        <div class="w-full md:w-1/2 p-10">
            <h1 class="text-3xl font-bold mb-6 text-purple-700 text-center">Registrasi Pasien</h1>
            <form method="POST" class="space-y-6">

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="username" name="username" placeholder="Nama Lengkap" required
                        class="w-full p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required
                        class="w-full p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="w-full p-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
                </div>

                <button type="submit" name="register"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-semibold transition">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center text-purple-600">
                Sudah punya akun?
                <a href="login.php" class="underline hover:text-purple-800 font-semibold">Login</a>
            </p>
        </div>
    </div>
</body>


</html>