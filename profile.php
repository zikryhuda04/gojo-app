<?php
session_start();
if (!isset($_SESSION['id'])) header("Location: index.php");
require 'koneksi.php';

$user_id = $_SESSION['id'];
$error = "";

// Handle upload foto profil
if (isset($_POST['upload_foto'])) {
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto_profil']['name'];
        $filetmp = $_FILES['foto_profil']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $newname = "profil_" . $user_id . "_" . time() . "." . $ext;
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $upload_path = $upload_dir . $newname;
            if (move_uploaded_file($filetmp, $upload_path)) {
                // Update database
                $stmt = $conn->prepare("UPDATE users SET foto_profil = ? WHERE id = ?");
                $stmt->bind_param("si", $newname, $user_id);
                $stmt->execute();
                $stmt->close();
                $_SESSION['foto_profil'] = $newname;
                header("Location: profile.php");
                exit;
            } else {
                $error = "Gagal mengupload file.";
            }
        } else {
            $error = "Format file tidak didukung. Hanya jpg, jpeg, png, gif.";
        }
    } else {
        $error = "File belum dipilih atau terjadi kesalahan upload.";
    }
}

// Ambil data user dari database
$stmt = $conn->prepare("SELECT nama, email, tempat_tgl_lahir, alamat, universitas, fakultas, prodi, no_hp, foto_profil FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Data default jika kosong
if (!$user) {
    $user = [
        'nama' => 'Zikry Huda Hermawan',
        'email' => 'zikryzikryhudahermawan@gmail.com',
        'tempat_tgl_lahir' => 'Pekanbaru, 28 Mei 2004',
        'alamat' => 'Pekanbaru',
        'universitas' => 'Universitas Negeri Padang',
        'fakultas' => 'Teknik',
        'prodi' => 'Informatika',
        'no_hp' => '087749062939',
        'foto_profil' => null
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Gojo - Profil</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    /* Style dasar */
    body {
      margin: 0; padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('gedung-rektorat-unp-kota-padang-sumbar-m-afdal-afriantodetiksumut_169.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      position: relative;
      min-height: 100vh;
      overflow-x: hidden;
    }
    .overlay {
      position: fixed;
      top:0; left:0; right:0; bottom:0;
      background: rgba(0,0,0,0.5);
      z-index: 0;
    }
    .container {
      display: flex;
      min-height: 100vh;
      position: relative;
      z-index: 2;
      opacity: 1;
      transition: opacity 0.5s ease;
    }
    /* Efek fade out sebelum pindah halaman */
    .container.fade-out {
      opacity: 0;
    }

    aside.sidebar {
      width: 240px;
      background: rgba(0,0,0,0.7);
      padding: 20px;
      box-sizing: border-box;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    aside.sidebar h2 {
      margin: 0 0 30px;
      font-weight: 700;
      font-size: 28px;
      user-select: none;
    }
    nav.sidebar-nav {
      display: flex;
      flex-direction: column;
      width: 100%;
    }
    nav.sidebar-nav a {
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      font-weight: 600;
      transition: background-color 0.3s, color 0.3s;
      user-select: none;
      cursor: pointer;
    }
    nav.sidebar-nav a:hover,
    nav.sidebar-nav a.active {
      background-color: #0077cc;
      color: #fff;
    }
    main.content {
      flex-grow: 1;
      padding: 60px 40px;
      max-width: 700px;
      margin: auto 40px;
      background: rgba(0,0,0,0.5);
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.6);
      user-select: none;
    }
    main.content h1 {
      font-size: 44px;
      margin-bottom: 25px;
      text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
      opacity: 0;
      transform: translateX(-20px);
      animation: slideFadeIn 0.7s forwards ease-out;
      animation-delay: 0.3s;
    }
    main.content p {
      font-size: 20px;
      line-height: 1.6;
      margin-bottom: 15px;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.6);
      opacity: 0;
      transform: translateX(-20px);
      animation: slideFadeIn 0.7s forwards ease-out;
    }
    main.content p strong {
      display: inline-block;
      width: 180px;
      color: #ddd;
    }
    /* Animasi delay untuk tiap paragraf */
    main.content p:nth-of-type(1) { animation-delay: 0.4s; }
    main.content p:nth-of-type(2) { animation-delay: 0.5s; }
    main.content p:nth-of-type(3) { animation-delay: 0.6s; }
    main.content p:nth-of-type(4) { animation-delay: 0.7s; }
    main.content p:nth-of-type(5) { animation-delay: 0.8s; }
    main.content p:nth-of-type(6) { animation-delay: 0.9s; }
    main.content p:nth-of-type(7) { animation-delay: 1.0s; }

    hr {
      margin: 30px 0;
      border: none;
      height: 1px;
      background: #0077cc;
      opacity: 0;
      transform: translateX(-20px);
      animation: slideFadeIn 0.7s forwards ease-out;
      animation-delay: 1.1s;
    }

    .error-msg {
      background: #cc0000;
      color: #fff;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      opacity: 0;
      transform: translateX(-20px);
      animation: slideFadeIn 0.7s forwards ease-out;
      animation-delay: 0.3s;
    }

    /* Animasi fade-in + scale pada foto profil */
    .profile-photo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #0077cc;
      margin-bottom: 20px;
      background: #222;
      display: block;
      opacity: 0;
      transform: scale(0.8);
      animation: fadeScaleIn 0.8s forwards ease-out;
      animation-delay: 0.2s;
    }
    @keyframes fadeScaleIn {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
    @keyframes slideFadeIn {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Tombol upload: animasi hover */
    .upload-form input[type=file] {
      padding: 8px;
      background: #eee;
      border-radius: 8px;
      border: none;
      width: 100%;
      margin-bottom: 12px;
      cursor: pointer;
      transition: box-shadow 0.3s ease;
    }
    .upload-form input[type=file]:focus {
      outline: none;
      box-shadow: 0 0 8px 2px #0077cc;
    }
    .upload-form button {
      background-color: #0077cc;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s ease;
      will-change: transform;
    }
    .upload-form button:hover {
      background-color: #005fa3;
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <div class="container" id="container">
    <aside class="sidebar">
      <h2>Jikliii</h2>
      <?php if ($user['foto_profil'] && file_exists("uploads/" . $user['foto_profil'])): ?>
        <img src="uploads/<?= htmlspecialchars($user['foto_profil']) ?>" alt="Foto Profil" class="profile-photo" />
      <?php else: ?>
        <img src="assets/default-profile.png" alt="Foto Profil Default" class="profile-photo" />
      <?php endif; ?>
      <nav class="sidebar-nav">
        <a href="home.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="courses.php">My Courses</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="logout.php">Logout</a>
      </nav>
    </aside>

    <main class="content">
      <h1>👤 Profil Pengguna</h1>

      <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Tempat, Tanggal Lahir:</strong> <?= htmlspecialchars($user['tempat_tgl_lahir']) ?></p>
      <p><strong>Alamat:</strong> <?= htmlspecialchars($user['alamat']) ?></p>
      <p><strong>Universitas:</strong> <?= htmlspecialchars($user['universitas']) ?></p>
      <p><strong>Fakultas:</strong> <?= htmlspecialchars($user['fakultas']) ?></p>
      <p><strong>Program Studi:</strong> <?= htmlspecialchars($user['prodi']) ?></p>
      <p><strong>No. HP:</strong> <?= htmlspecialchars($user['no_hp']) ?></p>

      <hr>

      <h2>Upload Foto Profil</h2>
      <form class="upload-form" method="POST" enctype="multipart/form-data" action="">
        <input type="file" name="foto_profil" accept=".jpg,.jpeg,.png,.gif" required />
        <button type="submit" name="upload_foto">Upload</button>
      </form>
    </main>
  </div>

  <script>
    // Animasi fade-in container saat halaman dimuat
    window.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('container');
      container.style.opacity = 0;
      setTimeout(() => {
        container.style.transition = 'opacity 0.5s ease';
        container.style.opacity = 1;
      }, 50);
    });

    // Ambil semua link navigasi
    const navLinks = document.querySelectorAll('nav.sidebar-nav a');
    const container = document.getElementById('container');

    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');

        // Tambahkan class fade-out pada container
        container.classList.add('fade-out');

        // Setelah 500ms (durasi transition), pindah ke halaman tujuan
        setTimeout(() => {
          window.location.href = href;
        }, 500);
      });
    });
  </script>
</body>
</html>
