<?php
session_start();
if (!isset($_SESSION['id'])) header("Location: index.php");
require 'koneksi.php';

$user_id = $_SESSION['id'];
$error = "";

// --- Handle tambah tugas ---
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_tugas'];
    $desk = $_POST['deskripsi'];
    $tgl = $_POST['deadline'];

    $file_name = null;
    if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $file_tmp = $_FILES['file_tugas']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed)) {
            $file_name = uniqid() . '.' . $file_ext;
            move_uploaded_file($file_tmp, 'uploads/' . $file_name);
        } else {
            $error = "Format file tidak didukung!";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO tugas (user_id, nama_tugas, deskripsi, deadline, file_tugas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $nama, $desk, $tgl, $file_name);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php");
        exit;
    }
}

// --- Handle hapus tugas ---
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $res = $conn->prepare("SELECT file_tugas FROM tugas WHERE id=? AND user_id=?");
    $res->bind_param("ii", $id, $user_id);
    $res->execute();
    $res->bind_result($file);
    $res->fetch();
    $res->close();

    if ($file && file_exists('uploads/' . $file)) {
        unlink('uploads/' . $file);
    }

    $stmt = $conn->prepare("DELETE FROM tugas WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit;
}

// --- Handle update tugas ---
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = $_POST['nama_tugas'];
    $desk = $_POST['deskripsi'];
    $tgl = $_POST['deadline'];

    // Ambil file lama untuk hapus jika diganti
    $res = $conn->prepare("SELECT file_tugas FROM tugas WHERE id=? AND user_id=?");
    $res->bind_param("ii", $id, $user_id);
    $res->execute();
    $res->bind_result($old_file);
    $res->fetch();
    $res->close();

    $file_name = $old_file;

    // Jika upload file baru
    if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $file_tmp = $_FILES['file_tugas']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            // Hapus file lama
            if ($old_file && file_exists('uploads/' . $old_file)) {
                unlink('uploads/' . $old_file);
            }
            $file_name = uniqid() . '.' . $file_ext;
            move_uploaded_file($file_tmp, 'uploads/' . $file_name);
        } else {
            $error = "Format file tidak didukung!";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE tugas SET nama_tugas=?, deskripsi=?, deadline=?, file_tugas=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssssii", $nama, $desk, $tgl, $file_name, $id, $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: dashboard.php");
        exit;
    }
}

// Ambil semua tugas user
$result = $conn->prepare("SELECT id, nama_tugas, deskripsi, deadline, file_tugas FROM tugas WHERE user_id=? ORDER BY deadline ASC");
$result->bind_param("i", $user_id);
$result->execute();
$tasks = $result->get_result();

// Jika edit mode, ambil data tugas yang ingin diedit
$edit_task = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT id, nama_tugas, deskripsi, deadline FROM tugas WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $edit_id, $user_id);
    $stmt->execute();
    $res_edit = $stmt->get_result();
    if ($res_edit->num_rows > 0) {
        $edit_task = $res_edit->fetch_assoc();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Gojo - Dashboard</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    /* Style sama seperti sebelumnya (sidebar, overlay, dll) */
    /* Style sama seperti sebelumnya (sidebar, overlay, dll) */
body, html {
  height: 100%;
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: url('gedung-rektorat-unp-kota-padang-sumbar-m-afdal-afriantodetiksumut_169.jpeg') no-repeat center center fixed;
  background-size: cover;
  color: white;
  overflow-x: hidden;
  position: relative;
  animation: fadeInPage 1s ease forwards;
}

@keyframes fadeInPage {
  from {opacity: 0;}
  to {opacity: 1;}
}

.overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(6px);
  pointer-events: none;
  z-index: 1;
}

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
  animation: slideInSidebar 0.6s ease forwards;
}

@keyframes slideInSidebar {
  from {
    transform: translateX(-100%);
    opacity: 0;
  } to {
    transform: translateX(0);
    opacity: 1;
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
  transition: color 0.3s ease, background-color 0.3s ease;
  padding: 8px 12px;
  border-radius: 6px;
}

aside.sidebar nav a:hover, aside.sidebar nav a.active {
  color: #00bfff;
  background-color: rgba(0, 191, 255, 0.15);
  text-decoration: underline;
}

main {
  margin-left: 240px;
  padding: 60px 40px;
  position: relative;
  z-index: 5;
  max-width: 900px;
  animation: fadeSlideDown 0.6s ease forwards;
}

@keyframes fadeSlideDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

main h1 {
  font-size: 42px;
  margin-bottom: 30px;
  text-shadow: 2px 2px 6px rgba(0,0,0,0.7);
  user-select: none;
}

form {
  animation: slideDownForm 0.6s ease forwards;
}

@keyframes slideDownForm {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

form input[type="text"], form input[type="date"], form textarea, form input[type="file"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 12px;
  border-radius: 8px;
  border: none;
  font-size: 16px;
  resize: vertical;
  background: rgba(255,255,255,0.9);
  color: #000;
  box-sizing: border-box;
  transition: box-shadow 0.3s ease;
}

form input[type="text"]:focus, form input[type="date"]:focus, form textarea:focus, form input[type="file"]:focus {
  outline: none;
  box-shadow: 0 0 8px #00bfff;
}

form button {
  background-color: #0077cc;
  color: white;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.15s ease;
  border: none;
  padding: 12px 0;
  border-radius: 8px;
  font-size: 18px;
  width: 100%;
  user-select: none;
}

form button:hover {
  background-color: #005fa3;
  transform: scale(1.05);
}

form button:active {
  transform: scale(0.98);
}

ul {
  list-style: none;
  padding-left: 0;
  margin-top: 30px;
}

ul li {
  background: rgba(255,255,255,0.15);
  margin-bottom: 15px;
  border-radius: 12px;
  padding: 20px;
  color: #000;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  opacity: 0;
  transform: translateY(15px);
  animation: fadeSlideUp 0.5s ease forwards;
  animation-delay: var(--anim-delay);
}

ul li:nth-child(1) { --anim-delay: 0.1s; }
ul li:nth-child(2) { --anim-delay: 0.2s; }
ul li:nth-child(3) { --anim-delay: 0.3s; }
ul li:nth-child(4) { --anim-delay: 0.4s; }
ul li:nth-child(5) { --anim-delay: 0.5s; }
ul li:nth-child(6) { --anim-delay: 0.6s; }
ul li:nth-child(7) { --anim-delay: 0.7s; }
ul li:nth-child(8) { --anim-delay: 0.8s; }
ul li:nth-child(9) { --anim-delay: 0.9s; }

@keyframes fadeSlideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

ul li .info {
  max-width: 70%;
}

ul li strong {
  font-size: 20px;
}

ul li small {
  display: block;
  margin-top: 4px;
  font-size: 14px;
  color: #333;
}

ul li span {
  display: block;
  margin-top: 6px;
  font-size: 16px;
  color: #222;
}

ul li a.hapus, ul li a.edit, ul li a.file-link {
  font-size: 14px;
  margin-left: 12px;
  padding: 8px 12px;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.3s ease, color 0.3s ease, transform 0.15s ease;
  user-select: none;
}

ul li a.hapus {
  color: #cc0000;
  background: rgba(255,0,0,0.15);
}

ul li a.hapus:hover {
  background: rgba(255,0,0,0.35);
  transform: scale(1.05);
}

ul li a.edit {
  background: rgba(0, 123, 255, 0.2);
  color: #007bff;
}

ul li a.edit:hover {
  background: rgba(0, 123, 255, 0.4);
  transform: scale(1.05);
}

ul li a.file-link {
  color: #0077cc;
  background: rgba(0,123,255,0.15);
}

ul li a.file-link:hover {
  background: rgba(0,123,255,0.35);
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
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="courses.php">My Courses</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>

  <main>
    <h1>Selamat datang di Dashboard, <?= htmlspecialchars($_SESSION['nama']) ?>!</h1>

    <?php if ($edit_task): ?>
      <h2>Edit Tugas</h2>
      <?php if ($error): ?>
        <p style="color: #ff6666; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit_task['id'] ?>" />
        <input type="text" name="nama_tugas" placeholder="Nama Tugas" required value="<?= htmlspecialchars($edit_task['nama_tugas']) ?>">
        <textarea name="deskripsi" placeholder="Deskripsi Tugas" required><?= htmlspecialchars($edit_task['deskripsi']) ?></textarea>
        <input type="date" name="deadline" required value="<?= htmlspecialchars($edit_task['deadline']) ?>">
        <input type="file" name="file_tugas" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        <button type="submit" name="update">Update Tugas</button>
        <a href="dashboard.php" style="display:inline-block; margin-top: 10px; color:#ccc; text-decoration:none;">Batal</a>
      </form>

    <?php else: ?>
      <h2>Tambah Tugas Baru</h2>
      <?php if ($error): ?>
        <p style="color: #ff6666; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <form method="POST" action="" enctype="multipart/form-data">
        <input type="text" name="nama_tugas" placeholder="Nama Tugas" required>
        <textarea name="deskripsi" placeholder="Deskripsi Tugas" required></textarea>
        <input type="date" name="deadline" required>
        <input type="file" name="file_tugas" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        <button type="submit" name="tambah">Tambah Tugas</button>
      </form>
    <?php endif; ?>

    <h2>Daftar Tugas</h2>
    <ul>
      <?php while ($row = $tasks->fetch_assoc()): ?>
      <li>
        <div class="info">
          <strong><?= htmlspecialchars($row['nama_tugas']) ?></strong>
          <small>Deadline: <?= htmlspecialchars($row['deadline']) ?></small>
          <span><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></span>
          <?php if ($row['file_tugas']): ?>
            <a href="uploads/<?= urlencode($row['file_tugas']) ?>" target="_blank" class="file-link">Lihat File</a>
          <?php endif; ?>
        </div>
        <div>
          <a href="?edit=<?= $row['id'] ?>" class="edit">Edit</a>
          <a href="?hapus=<?= $row['id'] ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus tugas ini?');">Hapus</a>
        </div>
      </li>
      <?php endwhile; ?>
    </ul>
  </main>
</body>
</html>
