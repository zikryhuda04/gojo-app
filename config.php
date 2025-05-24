<?php
$koneksi = new mysqli("localhost", "root", "", "gojo_db");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
