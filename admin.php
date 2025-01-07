<?php
// Memastikan session sudah aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

// Cek apakah user memiliki role admin
if ($_SESSION['role'] !== 'admin') {
    header("location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal | Admin</title>
    <link rel="icon" href="img/logo.png" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <style>
        .navbar-toggler-icon {
            background-color: white;
        }

        .bg-danger-subtle {
            background-color: #005D69 !important;
        }

        .border-danger-subtle {
            border-color: #005D69 !important;
        }

        .navbar .nav-link,
        .navbar-brand {
            color: white !important;
        }

        .navbar .nav-item.dropdown .dropdown-toggle {
            color: #D9534F !important;
        }

        .footer {
            background-color: #005D69 !important;
            color: white !important;
        }

        .footer a i {
            color: white !important;
        }

        .footer a i:hover {
            color: #cccccc !important;
        }
    </style>
</head>

<body>
    <!-- nav begin -->
    <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top bg-danger-subtle"
        style="border-radius: 15px 15px 15px 15px; margin: 10px 20px 0 20px;">
        <div class="container">
            <a class="navbar-brand" href=".">My Daily Journal</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=article">Article</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=user">Akun</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- nav end -->
    
    <!-- content begin -->
    <section id="content" class="p-5">
        <div class="container">
            <?php
            if (isset($_GET['page']) && file_exists($_GET['page'] . ".php")) {
                $page = htmlspecialchars($_GET['page']);
                ?>
                <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle"><?= ucfirst($page) ?></h4>
                <?php
                include($page . ".php");
            } else {
                ?>
                <h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">Dashboard</h4>
                <?php
                include("dashboard.php");
            }
            ?>
        </div>
    </section>
    <!-- content end -->

    <!-- footer begin -->
    <footer class="text-center p-1 bg-danger-subtle footer fixed-bottom">
        <div>
            <a href="https://www.instagram.com/udinusofficial"><i class="bi bi-instagram h6 p-1 text-dark"></i></a>
            <a href="https://twitter.com/udinusofficial"><i class="bi bi-twitter h6 p-1 text-dark"></i></a>
            <a href="https://wa.me/+62812685577"><i class="bi bi-whatsapp h6 p-1 text-dark"></i></a>
        </div>
        <div style="font-size: 0.8rem;">Singgih Naufal &copy; 2024</div>
    </footer>
    <!-- footer end -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
</html>
