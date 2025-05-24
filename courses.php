<?php
session_start();
if (!isset($_SESSION['id'])) header("Location: index.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Gojo - My Courses</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    /* Background full page dengan image dan overlay blur */
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
  /* Animasi fade-in saat load */
  animation: fadeInPage 1s ease forwards;
  opacity: 0;
}

@keyframes fadeInPage {
  to { opacity: 1; }
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
  /* Tambah animasi slide dari kiri */
  animation: slideInSidebar 0.6s ease forwards;
  transform: translateX(-100%);
}

@keyframes slideInSidebar {
  to { transform: translateX(0); }
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
  transition: color 0.3s ease, transform 0.3s ease;
}

aside.sidebar nav a:hover, aside.sidebar nav a.active {
  color: #00bfff;
  text-decoration: underline;
  transform: scale(1.05);
  transition: color 0.3s ease, transform 0.3s ease;
}

/* Main content di samping sidebar */
main {
  margin-left: 240px;
  padding: 60px 40px;
  position: relative;
  z-index: 5;
  max-width: 900px;
}

/* Judul utama */
main h1 {
  font-size: 42px;
  margin-bottom: 30px;
  text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
  /* Animasi fade in */
  opacity: 0;
  animation: fadeInUp 0.7s ease forwards;
  animation-delay: 0.5s;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Kotak mata kuliah buram */
.course-list {
  display: grid;
  grid-template-columns: repeat(auto-fill,minmax(280px,1fr));
  gap: 20px;
  opacity: 0;
  animation: fadeInUp 0.7s ease forwards;
  animation-delay: 0.8s;
}

.course-card {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
  color: white;
  font-weight: 600;
  font-size: 20px;
  text-align: center;
  transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
  user-select: none;
  cursor: default;
}

.course-card:hover {
  background: rgba(0, 191, 255, 0.3);
  box-shadow: 0 0 25px rgba(0,191,255,0.7);
  transform: scale(1.05);
}
  </style>
</head>
<body>
  <div class="overlay"></div>

  <aside class="sidebar">
    <h2>Jikliii</h2>
    <nav>
      <a href="home.php">Home</a>
      <a href="dashboard.php">Dashboard</a>
      <a href="courses.php" class="active">My Courses</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>

  <main>
    <h1>📚 My Courses</h1>
    <div class="course-list">
      <div class="course-card">Perancangan dan Analisis Algoritma</div>
      <div class="course-card">Kriptografi</div>
      <div class="course-card">Teori Bahasa dan Automata</div>
      <div class="course-card">Rekayasa Perangkat Lunak</div>
      <div class="course-card">Statistik dan Probabilitas</div>
      <div class="course-card">Bahasa Inggris Informatika</div>
      <div class="course-card">Praktikum Basis Data</div>
      <div class="course-card">Praktikum Artificial Intelligence</div>
      <div class="course-card">Administrasi Sistem Jaringan</div>
      <div class="course-card">Data Science</div>
      <div class="course-card">Etika Profesi</div>
    </div>
  </main>
</body>
</html>
