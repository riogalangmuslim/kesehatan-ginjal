<?php
$host = "localhost";
$user = "root";   // ganti sesuai XAMPP/Laragon
$pass = "";
$db   = "ginjal";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}
?>
