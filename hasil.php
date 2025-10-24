<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pasien
$stmt = $mysqli->prepare("SELECT nama, usia FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Ambil hasil diagnosa pasien
$stmt = $mysqli->prepare("SELECT * FROM hasil_diagnosa WHERE user_id=? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$diagnosa = $stmt->get_result();
$stmt->close();

// Fungsi logika tingkat kesehatan
function tingkatKesehatan($diagnosis, $gejala) {
    $jumlahGejala = !empty($gejala) ? count(explode(",", $gejala)) : 0;

    if (stripos($diagnosis, "berat") !== false || $jumlahGejala >= 6) {
        return "Buruk";
    } elseif (stripos($diagnosis, "sedang") !== false || $jumlahGejala >= 3) {
        return "Cukup";
    } else {
        return "Baik";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Diagnosa Pasien</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f9f9f9; margin:0; padding:20px;">

<!-- Sidebar -->
<div style="width:220px;background:#4b6edc;color:white;height:100vh;
            padding:20px 0;position:fixed;left:0;top:0;
            display:flex;flex-direction:column;">
    
    <h2 style="text-align:center;margin-bottom:30px;">Dashboard Pasien</h2>
    
    <a href="dashboard_visimisi.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Informasi</a>
    <a href="dashboard.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Data Pasien</a>
    <a href="hasil.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;background:#3a57b3;">Hasil Diagnosa</a>
    <a href="logout.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Keluar</a>
</div>

<!-- Konten -->
<div style="margin-left:240px;">
    <h2 style="background:#4b6edc; color:white; padding:12px 20px; border-radius:8px; margin-top:0;">
        Hasil Diagnosa
    </h2>
    <table style="width:100%; border-collapse:collapse; margin-top:15px; background:white;
           box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:8px; overflow:hidden;">
        <tr>
            <th style="padding:12px; background:#f0f0f0; text-align:left;">Nama</th>
            <th style="padding:12px; background:#f0f0f0; text-align:left;">Usia</th>
            <th style="padding:12px; background:#f0f0f0; text-align:left;">Gejala</th>
            <th style="padding:12px; background:#f0f0f0; text-align:left;">Diagnosa</th>
            <th style="padding:12px; background:#f0f0f0; text-align:left;">Tingkat Kesehatan</th>
            <th style="padding:12px; background:#f0f0f0; text-align:left;">Jam Terakhir Cek</th>
        </tr>
        <?php if ($diagnosa->num_rows > 0): ?>
            <?php while ($row = $diagnosa->fetch_assoc()): ?>
                <tr style="border-bottom:1px solid #ddd;">
                    <td style="padding:12px;"><?= htmlspecialchars($user['nama'] ?? '') ?></td>
                    <td style="padding:12px;"><?= htmlspecialchars((string)($user['usia'] ?? '')) ?></td>
                    <td style="padding:12px;"><?= htmlspecialchars($row['gejala'] ?? '') ?></td>
                    <td style="padding:12px;"><?= htmlspecialchars($row['diagnosis'] ?? '') ?></td>
                    <td style="padding:12px; font-weight:bold;"><?= tingkatKesehatan($row['diagnosis'], $row['gejala']) ?></td>
                    <td style="padding:12px;"><?= htmlspecialchars(date("H:i:s", strtotime($row['created_at'] ?? ''))) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;color:#777;padding:12px;">Belum ada hasil diagnosa</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
