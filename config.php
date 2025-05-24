<?php
$servername = getenv('DB_HOST') ?: 'db'; // nama service di docker-compose
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: 'root'; // sesuaikan dengan environment di docker-compose
$dbname     = getenv('DB_NAME') ?: 'gojo_db';
$port       = getenv('DB_PORT') ?: 3306; // tambahkan port untuk MySQL

$koneksi = new mysqli($servername, $username, $password, $dbname, $port);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
