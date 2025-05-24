<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

require 'koneksi.php'; // Kalau gak perlu koneksi di sini, bisa dihapus
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Gojo - Home</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    /* Background full page dengan image dan overlay blur */
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('gedung-rektorat-unp-kota-padang-sumbar-m-afdal-afriantodetiksumut_169.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      position: relative;
      overflow-x: hidden;
      /* Tambah efek transisi warna background dan teks */
      transition: background-color 0.5s ease, color 0.5s ease;
    }
    
    /* Overlay gelap dengan pointer-events none agar tidak menghalangi klik */
    .overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      pointer-events: none;
      z-index: 1;
      opacity: 0;
      animation: fadeInOverlay 1s forwards;
    }

    @keyframes fadeInOverlay {
      to {
        opacity: 1;
      }
    }

    /* Sidebar navigasi */
    aside.sidebar {
      position: fixed;
      top: 0; left: 0; bottom: 0;
      width: 220px;
      background: rgba(0, 0, 0, 0.75);
      padding: 30px 20px;
      box-sizing: border-box;
      z-index: 10;
      display: flex;
      flex-direction: column;
      color: white;
      /* Animasi slide-in dari kiri */
      transform: translateX(-100%);
      animation: slideInSidebar 0.7s forwards ease-out;
    }

    @keyframes slideInSidebar {
      to {
        transform: translateX(0);
      }
    }

    aside.sidebar h2 {
      margin: 0 0 30px 0;
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 1.5px;
      user-select: none;
    }
    aside.sidebar nav a {
      display: block;
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      margin-bottom: 20px;
      transition: color 0.3s;
    }
    aside.sidebar nav a:hover, aside.sidebar nav a.active {
      color: #00bfff;
      text-decoration: underline;
    }

    /* Main content di samping sidebar */
    main {
      margin-left: 240px;
      padding: 60px 40px;
      position: relative;
      z-index: 5;
      max-width: 900px;
      /* Animasi fade-in */
      opacity: 0;
      animation: fadeInContent 1s forwards 0.5s;
    }

    @keyframes fadeInContent {
      to {
        opacity: 1;
      }
    }

    main h1 {
      font-size: 42px;
      margin-bottom: 30px;
      text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
    }

    /* Konten utama contoh */
    .welcome-box {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      color: white;
      font-size: 20px;
      font-weight: 600;
      text-align: center;
      user-select: none;
      /* Animasi slide-up */
      transform: translateY(20px);
      opacity: 0;
      animation: slideUpFadeIn 0.7s forwards 1.2s;
    }

    @keyframes slideUpFadeIn {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    /* Transisi link navigasi saat pindah halaman */
    aside.sidebar nav a {
      cursor: pointer;
    }
  </style>

  <script>
    // Tambah efek transisi halaman saat klik link navigasi
    document.addEventListener("DOMContentLoaded", function() {
      const links = document.querySelectorAll("aside.sidebar nav a");
      links.forEach(link => {
        link.addEventListener("click", function(e) {
          e.preventDefault();
          const href = this.getAttribute("href");
          // Animasi fade out sebelum pindah halaman
          document.body.style.transition = "opacity 0.5s ease";
          document.body.style.opacity = 0;
          setTimeout(() => {
            window.location.href = href;
          }, 500);
        });
      });
    });
  </script>
</head>
<body>
  <div class="overlay"></div>

  <aside class="sidebar">
    <h2>Jikliii</h2>
    <nav>
      <a href="home.php" class="active">Home</a>
      <a href="dashboard.php">Dashboard</a>
      <a href="courses.php">My Courses</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>

  <main>
    <h1>🏠 Welcome, <?= htmlspecialchars($_SESSION['nama']) ?>!</h1>
    <div class="welcome-box">
      Selamat datang di aplikasi terbaik, pusat pengelolaan tugas dan pembelajaran Anda.<br>
      Gunakan navigasi di sebelah kiri untuk mengakses dashboard, mata kuliah, profil, dan fitur lainnya.
    </div>
  </main>
</body>
</html>
