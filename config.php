<?php
$servername = getenv('DB_HOST') ?: 'db';    // nama service db di docker-compose
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'root';
$dbname   = getenv('DB_NAME') ?: 'gojo_db';

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
