<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Update data (alamat, usia, jenis kelamin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_data'])) {
    $alamat = trim($_POST['alamat']);
    $usia = intval($_POST['usia']);
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';

    if (!empty($alamat) && $usia > 0 && !empty($jenis_kelamin)) {
        $stmt = $mysqli->prepare("UPDATE users SET alamat=?, usia=?, jenis_kelamin=? WHERE id=?");
        $stmt->bind_param("sisi", $alamat, $usia, $jenis_kelamin, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Alamat, Usia, dan Jenis Kelamin wajib diisi!";
    }
}

// Delete data user
if (isset($_POST['delete_data'])) {
    $stmt = $mysqli->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Ambil data user
$stmt = $mysqli->prepare("SELECT id, nama, nik, email, alamat, usia, jenis_kelamin, created_at FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$alamatKosong = empty($user['alamat']);
$usiaKosong = empty($user['usia']);
$jkKosong = empty($user['jenis_kelamin']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Pasien</title>
</head>

<body style="margin:0;font-family:Arial,sans-serif;display:flex;">

    <!-- Sidebar -->
    <div style="width:220px;background:#4b6edc;color:white;height:100vh;
            padding:20px 0;position:fixed;left:0;top:0;
            display:flex;flex-direction:column;">

        <h2 style="text-align:center;margin-bottom:30px;">Dashboard Pasien</h2>

        <a href="dashboard_visimisi.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;">Informasi</a>
        <a href="dashboard.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;background:#3a57b3;">Data Pasien</a>
        <a href="hasil.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Hasil Diagnosa</a>
        <a href="logout.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Keluar</a>
    </div>

    <!-- Konten -->
    <div style="margin-left:220px;padding:20px;width:100%;background:#f9f9f9;min-height:100vh;">
        <h3 style="background:#f9f36c;display:inline-block;padding:10px 20px;border-radius:8px;">DATA DIRI PASIEN</h3>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" style="margin-top:20px;">
            <input type="hidden" name="update_data" value="1">

            <table style="width:100%;border-collapse:collapse;background:white;
                   box-shadow:0 6px 15px rgba(0,0,0,0.1);overflow:hidden;
                   border-radius:10px;font-size:15px;">
                <thead>
                    <tr style="background:linear-gradient(90deg,#4b6edc,#3551a0);color:white;">
                        <th style="padding:14px;text-align:center;">No.</th>
                        <th style="padding:14px;text-align:center;">Nama</th>
                        <th style="padding:14px;text-align:center;">NIK</th>
                        <th style="padding:14px;text-align:center;">Alamat</th>
                        <th style="padding:14px;text-align:center;">Usia</th>
                        <th style="padding:14px;text-align:center;">Jenis Kelamin</th>
                        <th style="padding:14px;text-align:center;">Tanggal</th>
                        <th style="padding:14px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($user): ?>
                        <tr style="transition:all 0.3s ease;"
                            onmouseover="this.style.background='#eef3ff';this.style.boxShadow='inset 0 0 8px rgba(0,0,0,0.08)';"
                            onmouseout="this.style.background='white';this.style.boxShadow='none';">
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">1</td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">
                                <?= htmlspecialchars($user['nama'] ?? '') ?>
                            </td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">
                                <?= htmlspecialchars($user['nik'] ?? '') ?>
                            </td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">
                                <input type="text" name="alamat" value="<?= htmlspecialchars($user['alamat'] ?? '') ?>"
                                    required style="width:90%;padding:8px;border:1px solid #ccc;border-radius:6px;
                                      transition:0.3s;"
                                    onfocus="this.style.borderColor='#4b6edc';this.style.boxShadow='0 0 5px rgba(75,110,220,0.5)';"
                                    onblur="this.style.borderColor='#ccc';this.style.boxShadow='none';">
                            </td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">
                                <input type="number" name="usia"
                                    value="<?= htmlspecialchars((string) ($user['usia'] ?? '')) ?>" required min="1"
                                    style="width:80px;padding:8px;border:1px solid #ccc;border-radius:6px;text-align:center;transition:0.3s;"
                                    onfocus="this.style.borderColor='#4b6edc';this.style.boxShadow='0 0 5px rgba(75,110,220,0.5)';"
                                    onblur="this.style.borderColor='#ccc';this.style.boxShadow='none';">
                            </td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">
                                <select name="jenis_kelamin" required
                                    style="padding:8px;border:1px solid #ccc;border-radius:6px;transition:0.3s;"
                                    onfocus="this.style.borderColor='#4b6edc';this.style.boxShadow='0 0 5px rgba(75,110,220,0.5)';"
                                    onblur="this.style.borderColor='#ccc';this.style.boxShadow='none';">
                                    <option value="">--Pilih--</option>
                                    <option value="Laki-laki" <?= ($user['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="Perempuan" <?= ($user['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;">
                                <?= htmlspecialchars($user['created_at'] ?? '-') ?>
                            </td>
                            <td style="border-bottom:1px solid #eee;padding:12px;text-align:center;white-space:nowrap;">
                                <button type="submit" style="padding:8px 16px;background:#28a745;color:white;border:none;
                                       border-radius:6px;cursor:pointer;font-weight:bold;
                                       transition:0.3s;box-shadow:0 2px 4px rgba(0,0,0,0.2);"
                                    onmouseover="this.style.background='#218838';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.3)';"
                                    onmouseout="this.style.background='#28a745';this.style.boxShadow='0 2px 4px rgba(0,0,0,0.2)';">
                                    Simpan
                                </button>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_data" value="1">
                                    <button type="submit" onclick="return confirm('Yakin hapus akun ini?')" style="padding:8px 16px;background:#dc3545;color:white;border:none;
                                           border-radius:6px;cursor:pointer;font-weight:bold;
                                           transition:0.3s;box-shadow:0 2px 4px rgba(0,0,0,0.2);margin-left:6px;"
                                        onmouseover="this.style.background='#b52b3b';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.3)';"
                                        onmouseout="this.style.background='#dc3545';this.style.boxShadow='0 2px 4px rgba(0,0,0,0.2)';">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="padding:20px;text-align:center;color:#777;">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>

        <div style="margin-top:15px;">
            <a href="gejala.php"
                style="display:inline-block;margin:10px 5px 0 0;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-weight:bold;background:red;color:white;">Mulai
                Cek Sekarang</a>
        </div>
    </div>

    <script>
        // Matikan sidebar kalau data wajib kosong
        const alamatKosong = <?= $alamatKosong ? 'true' : 'false' ?>;
        const usiaKosong = <?= $usiaKosong ? 'true' : 'false' ?>;
        const jkKosong = <?= $jkKosong ? 'true' : 'false' ?>;
        if (alamatKosong || usiaKosong || jkKosong) {
            document.querySelectorAll(".sidebar-link").forEach(link => {
                if (!link.href.includes("dashboard.php") && !link.href.includes("logout.php")) {
                    link.style.pointerEvents = "none";
                    link.style.opacity = "0.5";
                }
            });
            alert("Silakan lengkapi Alamat, Usia, dan Jenis Kelamin terlebih dahulu sebelum mengakses menu lain.");
        }
    </script>
</body>

</html>