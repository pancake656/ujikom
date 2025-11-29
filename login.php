<?php
session_start();
include 'koneksi.php';

// CEK: Jika sudah login, jangan boleh akses halaman login lagi, langsung lempar ke index
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: index.php");
    exit;
}

$pesan_error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek User di Database
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");
    
    if (mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_assoc($cek);
        
        // Simpan Session
        $_SESSION['username'] = $data['username'];
        $_SESSION['status'] = "login";
        
        // Login Berhasil -> Masuk ke Dashboard
        header("Location: index.php");
    } else {
        $pesan_error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="card card-login">
        <div class="card-body p-4">
            <h3 class="text-center mb-4 fw-bold">Login SIMBS</h3>
            
            <?php if($pesan_error != ""): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $pesan_error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="login" class="btn btn-primary">Masuk</button>
                </div>
            </form>
            
            <div class="text-center mt-3">
                <small>Belum punya akun? <a href="register.php">Daftar disini</a></small>
            </div>
        </div>
    </div>

</body>
</html>