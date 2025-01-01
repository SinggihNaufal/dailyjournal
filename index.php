<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Daily Journal</title>
  <link
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    rel="stylesheet" />
  <style>
    html {
      scroll-behavior: smooth;
    }

    section {
      padding-top: 80px;
    }

    .hero-text {
      max-width: 60%;
      color: white;
    }
    #home {
      scroll-margin-top: 80px;
    }
    .navbar-toggler-icon {
    background-color: white;
    }

    .hero-section {
      padding-top: 20px;
      background-color: #005D69;
      padding-bottom: 20px;
      margin-top: 80px;
      display: flex;
      align-items: center; 
    }
    .hero-section .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

    .hero-section img {
    margin-left: 20px;
    }

    .navbar-custom .nav-link {
    font-weight: 500; 
    }

    .navbar-custom {
    background-color: #005D69;
    color: white;
    }

    .navbar-custom .navbar-brand {
    color: white !important;
    }

    .navbar-custom .navbar-brand:hover {
    color: #d8d8d8 !important;
    }

    .navbar-custom .nav-link {
    color: white;
    }
    .navbar-custom .nav-link:hover {
    color: #d8d8d8;
    }
  </style>
</head>

<body>
  <!-- Navbar Bang-->
   <nav
   class="navbar fixed-top navbar-expand-lg navbar-custom shadow-sm"
   style="border-radius: 15px; margin: 10px 20px 0 20px;">
    <a class="navbar-brand" href=".">My Daily Journal</a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item">
          <a class="nav-link" href="#article">Article</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#gallery">Gallery</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#jadwal">Jadwal</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#profile">Profile</a>
        </li>
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="authDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            style="color: red;">
            User
          </a>
          <div class="dropdown-menu" aria-labelledby="authDropdown">
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>


  <!-- Hero Section Bang -->
  <section id="home" class="hero-section d-flex align-items-center">
    <div class="container d-flex justify-content-between">
      <div class="hero-text">
        <h1>Welcome to Web Daily Journal</h1>
        <p>Temukan karya unik yang membawa imajinasi anda melampui batas</p>
      </div>
      <img
        src="image/gambar1.png"
        width="300"
        height="300"
        alt="Hero Image" />
    </div>
  </section>

  <!-- Carousel Section bang-->
  <section id="carouselSection" class="my-5">
    <div
      id="carouselExampleIndicators"
      class="carousel slide"
      data-ride="carousel">
      <ol class="carousel-indicators">
        <li
          data-target="#carouselExampleIndicators"
          data-slide-to="0"
          class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img
            src="image/gambar2.jpg"
            width="1200"
            height="600"
            class="d-block w-100"
            alt="Slide 1" />
        </div>
        <div class="carousel-item">
          <img
            src="image/gambar3.jpg"
            width="1200"
            height="600"
            class="d-block w-100"
            alt="Slide 2" />
        </div>
        <div class="carousel-item">
          <img
            src="image/gambar4.jpg"
            width="1200"
            height="600"
            class="d-block w-100"
            alt="Slide 3" />
        </div>
      </div>
      <a
        class="carousel-control-prev"
        href="#carouselExampleIndicators"
        role="button"
        data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a
        class="carousel-control-next"
        href="#carouselExampleIndicators"
        role="button"
        data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </section>

  <!-- Article Section Bang-->
  <section id="article" class="container my-5">
    <h2 class="text-center">Article</h2>
    <div class="row">
      <?php
      $sql = "SELECT * FROM article ORDER BY tanggal DESC";
      $hasil = $conn->query($sql);

      while ($row = $hasil->fetch_assoc()) {
      ?>
        <div class="col">
          <div class="card h-100">
            <img src="img/<?= $row["gambar"] ?>" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title"><?= $row["judul"] ?></h5>
              <p class="card-text">
                <?= $row["isi"] ?>
              </p>
            </div>
            <div class="card-footer">
              <small class="text-body-secondary">
                <?= $row["tanggal"] ?>
              </small>
            </div>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </section>


  <!-- Gallery Section Bang -->
  <section id="gallery" class="container my-5">
    <h2 class="text-center">Gallery</h2>
    <div class="row">
      <div class="col-md-4">
        <img
          src="image/Galeri1.jpg"
          width="350"
          height="150"
          class="img-fluid mb-3"
          alt="Gallery Image 1" />
      </div>
      <div class="col-md-4">
        <img
          src="image/Galeri2.jpg"
          width="350"
          height="150"
          class="img-fluid mb-3"
          alt="Gallery Image 2" />
      </div>
      <div class="col-md-4">
        <img
          src="image/Galeri3.jpg"
          width="350"
          height="150"
          class="img-fluid mb-3"
          alt="Gallery Image 3" />
      </div>
    </div>
  </section>

  <!-- Jadwal Section Bang-->
  <section id="jadwal" class="schedule-section container">
    <h2 class="text-center mb-5">Jadwal Kuliah</h2>
    <div class="row">
      <div class="col-md-3 mb-4">
        <div class="card schedule-card text-center bg-primary text-white">
          <div class="card-body">
            <h5 class="card-title">Senin</h5>
            <p>09:30 - 12:00<br />logika Informatika<br />Ruang 4.4.11</p>
            <p>12:40 - 14:10<br />Basis data<br />Ruang H.4.3</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card schedule-card text-center bg-success text-white">
          <div class="card-body">
            <h5 class="card-title">Selasa</h5>
            <p>09:30 - 12:00<br />Sistem informasi<br />Ruang H.4.2</p>
            <p>
              12:30 - 14:10<br />Pemrograman Berbasis Web<br />Ruang D.2.j
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card schedule-card text-center bg-danger text-white">
          <div class="card-body">
            <h5 class="card-title">Rabu</h5>
            <p>10:00 - 12:00<br />Rekayasa perangkat lunak<br />Ruang D2.3</p>
            <p>13:00 - 15:00<br />Statistik<br />Ruang D1.1</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card schedule-card text-center bg-warning text-white">
          <div class="card-body">
            <h5 class="card-title">Kamis</h5>
            <p>08:00 - 10:00<br />Basis data<br />Ruang E2.1</p>
            <p>15:00 - 17:00<br />Sistem Operasi<br />Ruang E3.1</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card schedule-card text-center bg-info text-white">
          <div class="card-body">
            <h5 class="card-title">Jumat</h5>
            <p>
              07:00 - 11:00<br />Probabalitas Dan Statistik<br />Ruang D2.2
            </p>
            <p>13:00 - 15:00<br />basis data<br />Ruang D2.4</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--profile Bang-->
  <section id="profile" class="profile-section container">
    <h2 class="text-center mb-5">Profil Saya</h2>
    <div class="row align-items-center">
      <div class="col-md-4 text-center mb-4 mb-md-0">
        <img
          src="image/Untitled-1klh.jpg"
          alt="Foto Mahasiswa"
          class="profile-photo img-fluid rounded rounded-circle" />
      </div>
      <div class="col-md-8">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <th scope="row">Nama</th>
              <td>Singgih Naufal</td>
            </tr>
            <tr>
              <th scope="row">NIM</th>
              <td>A11.2023.15300</td>
            </tr>
            <tr>
              <th scope="row">Program Studi</th>
              <td>Teknik Informatika</td>
            </tr>
            <tr>
              <th scope="row">Email</th>
              <td>Singgihnaufal@gmail.com</td>
            </tr>
            <tr>
              <th scope="row">Telepon</th>
              <td>085879439124</td>
            </tr>
            <tr>
              <th scope="row">Alamat</th>
              <td>semarang timur</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
  <br />

  <!-- Footer Bang-->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0">&copy; 2024 Singgih Naufal</p>
  </footer>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>