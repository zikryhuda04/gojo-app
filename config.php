<?php
$servername = getenv('DB_HOST') ?: 'db';       // Nama service db di docker-compose
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: 'root'; // Sesuaikan dengan MYSQL_ROOT_PASSWORD
$dbname     = getenv('DB_NAME') ?: 'gojo_db';

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
