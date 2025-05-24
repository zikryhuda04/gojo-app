<?php
$servername = 'db'; // gunakan nama service database dari docker-compose
$username = 'root';
$password = 'root'; // sesuai docker-compose
$dbname = 'gojo_db';

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
