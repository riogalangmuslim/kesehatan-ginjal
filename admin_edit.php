<?php
session_start();
require 'db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Ambil data user
$stmt = $mysqli->prepare("SELECT nama, nik, email FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nama, $nik, $email);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $email = $_POST['email'];

    $stmt = $mysqli->prepare("UPDATE users SET nama=?, nik=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $nama, $nik, $email, $id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pasien</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning">Edit Pasien</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
                </div>
                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($nik) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <button class="btn btn-primary">Simpan</button>
                <a href="admin_dashboard.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
