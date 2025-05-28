<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gojo_db";

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal (XAMPP): " . $koneksi->connect_error);
}
?>
