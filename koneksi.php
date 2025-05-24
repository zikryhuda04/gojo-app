<?php
$host = "localhost";
$user = "root";
$pass = ""; // default XAMPP tidak pakai password
$db   = "gojo_db"; // ✅ ganti sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>
