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
    // Mengambil input dari form login
    $username = $_POST['username'];
    $password = md5($_POST['password']);  // Menggunakan md5 untuk password

    // Prepared statement untuk mengambil username dan password yang sesuai
    $stmt = $conn->prepare("SELECT username, role FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);  // Bind parameter: username dan password

    // Eksekusi statement
    $stmt->execute();

    // Mendapatkan hasil
    $hasil = $stmt->get_result();
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    // Jika data ditemukan dan username serta password cocok
    if (!empty($row)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];  // Simpan role user di session

        // Memeriksa role dan mengarahkan ke halaman yang sesuai
        if ($row['role'] == 'admin') {
            // Jika role adalah admin, arahkan ke halaman admin
            header("location:admin.php"); 
        } elseif ($row['role'] == 'user') {
            // Jika role adalah user, arahkan ke halaman index
            header("location:index.php"); 
        } else {
            // Jika role tidak dikenali, kembalikan ke login
            header("location:login.php");
        }
    } else {
        // Jika login gagal (username atau password salah)
        $_SESSION['error'] = "Username atau password salah!";
        header("location:login.php"); // Kembali ke halaman login
    }

    // Menutup statement dan koneksi database
    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
