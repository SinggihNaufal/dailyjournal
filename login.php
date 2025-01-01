<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #005D69; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-form {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2 class="text-center mb-4">Welcome Back!</h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    <?php
// Memulai session atau melanjutkan session yang sudah ada
session_start();

// Menyertakan file koneksi database
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Menggunakan md5 untuk mencocokkan password di database
    $password = md5($_POST['password']);

    // Prepared statement untuk mengambil username dan role
    $stmt = $conn->prepare("SELECT username, role FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password); // username string, password string

    // Eksekusi statement
    $stmt->execute();

    // Mendapatkan hasil
    $hasil = $stmt->get_result();
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    // Jika ada data yang cocok
    if (!empty($row)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // Memeriksa role dan mengarahkan ke halaman yang sesuai
        if ($row['role'] == 'admin') {
            header("location:admin.php"); // Halaman admin
        } elseif ($row['role'] == 'user') {
            header("location:index.php"); // Halaman user biasa
        } else {
            // Jika role tidak dikenali, kembali ke login
            header("location:login.php");
        }
    } else {
        // Jika login gagal
        header("location:login.php");
    }

    // Menutup statement dan koneksi database
    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
