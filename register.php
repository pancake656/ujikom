<?php
include 'koneksi.php';

$pesan_error = "";
$pesan_sukses = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // Validasi 1: Cek apakah password dan konfirmasi sama?
    if ($password != $konfirmasi) {
        $pesan_error = "Konfirmasi password tidak sesuai!";
    } 
    // Validasi 2: Password minimal 8 karakter
    elseif (strlen($password) < 8) {
        $pesan_error = "Password harus mengandung minimal 8 karakter";
    } 
    else {
        // Validasi 3: Cek username/email duplikat
        $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan_error = "Username atau email sudah terdaftar!";
        } else {
            // Jika aman, simpan data
            $query = mysqli_query($koneksi, "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')");
            if ($query) {
                // Redirect ke login dengan pesan sukses (opsional) atau tampilkan alert
                echo "<script>alert('Register Berhasil! Silakan Login.'); window.location='login.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px; /* Agar tidak mepet saat di HP */
        }
        .card-custom {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: none;
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
            background-color: #fff;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            background-color: #2563eb;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
        }
        h2 {
            font-weight: 600;
            color: #2d3748;
        }
    </style>
</head>
<body>

    <div class="card-custom">
        <div class="card-body">
            <h2 class="text-center mb-4">Register</h2>

            <?php if($pesan_error != ""): ?>
                <div class="alert alert-danger p-2 text-center" style="font-size: 14px;">
                    <?= $pesan_error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email..." required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password..." required>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </div>

                <div class="text-start">
                    <span class="text-muted" style="font-size: 14px;">Sudah punya akun? <a href="login.php" class="text-decoration-none text-primary">Login</a></span>
                </div>
            </form>
        </div>
    </div>

</body>
</html>