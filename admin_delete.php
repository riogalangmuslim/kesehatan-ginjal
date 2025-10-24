<?php
session_start();
require 'db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Hapus pasien & riwayatnya
$stmt = $mysqli->prepare("DELETE FROM hasil_diagnosa WHERE user_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt = $mysqli->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_dashboard.php");
exit;
