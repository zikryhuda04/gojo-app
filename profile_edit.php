<?php
session_start();
require 'koneksi.php';  // koneksi database

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['id'];

// Ambil data user saat ini
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $ttl = mysqli_real_escape_string($conn, $_POST['tempat_tanggal_lahir']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $universitas = mysqli_real_escape_string($conn, $_POST['universitas']);
    $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
    $prodi = mysqli_real_escape_string($conn, $_POST['prodi']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    // Update data user
    $sql = "UPDATE users SET 
            nama='$nama',
            email='$email',
            tempat_tanggal_lahir='$ttl',
            alamat='$alamat',
            universitas='$universitas',
            fakultas='$fakultas',
            prodi='$prodi',
            no_hp='$no_hp'
            WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        // Update session supaya data terbaru tampil
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['tempat_tanggal_lahir'] = $ttl;
        $_SESSION['alamat'] = $alamat;
        $_SESSION['universitas'] = $universitas;
        $_SESSION['fakultas'] = $fakultas;
        $_SESSION['prodi'] = $prodi;
        $_SESSION['no_hp'] = $no_hp;

        header("Location: profile.php?update=success");
        exit;
    } else {
        $error = "Gagal update profil: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Profil - Gojo</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container fade-in">
    <aside class="sidebar">
      <h2>Gojo</h2>
      <nav>
        <a href="home.php"> Home</a>
        <a href="dashboard.php"> Dashboard</a>
        <a href="courses.php"> My Courses</a>
        <a href="profile.php"> Profile</a>
        <a href="logout.php"> Logout</a>
      </nav>
    </aside>

    <main>
      <h1>Edit Profil</h1>

      <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

      <form method="POST" action="">
        <label>Nama:</label><br>
        <input type="text" name="nama" required value="<?= htmlspecialchars($user['nama']) ?>"><br>

        <label>Email:</label><br>
        <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"><br>

        <label>Tempat, Tanggal Lahir:</label><br>
        <input type="text" name="tempat_tanggal_lahir" value="<?= htmlspecialchars($user['tempat_tanggal_lahir']) ?>"><br>

        <label>Alamat:</label><br>
        <input type="text" name="alamat" value="<?= htmlspecialchars($user['alamat']) ?>"><br>

        <label>Universitas:</label><br>
        <input type="text" name="universitas" value="<?= htmlspecialchars($user['universitas']) ?>"><br>

        <label>Fakultas:</label><br>
        <input type="text" name="fakultas" value="<?= htmlspecialchars($user['fakultas']) ?>"><br>

        <label>Prodi:</label><br>
        <input type="text" name="prodi" value="<?= htmlspecialchars($user['prodi']) ?>"><br>

        <label>No HP:</label><br>
        <input type="text" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>"><br><br>

        <button type="submit">Simpan Perubahan</button>
      </form>
    </main>
  </div>
</body>
</html>
