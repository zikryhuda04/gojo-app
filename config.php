<?php
$servername = getenv('MYSQLHOST') ?: 'localhost';
$username   = getenv('MYSQLUSER') ?: 'root';
$password   = getenv('MYSQLPASSWORD') ?: '';
$dbname     = getenv('MYSQLDATABASE') ?: 'gojo_db';

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
