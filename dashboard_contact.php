<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
</head>

<body style="margin:0; font-family:Arial, sans-serif;">

    <!-- Sidebar -->
    <div style="width:220px;background:#4b6edc;color:white;height:100vh;padding:20px 0;position:fixed;left:0;top:0;">
        <h2 style="text-align:center;margin-bottom:30px;">Dashboard Pasien</h2>
        <a href="dashboard_visimisi.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">VISI
            & MISI</a>
        <a href="dashboard.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">DATA
            PASIEN</a>
        <a href="hasil.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">HASIL DIAGNOSA</a>
        <a href="dashboard_contact.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;">CONTACT</a>
        <div style="position:absolute;bottom:20px;width:100%;">
            <a href="logout.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">LOGOUT</a>
        </div>
    </div>

    <!-- Konten -->
    <div
        style="margin-left:240px; padding:30px; background:url('bg.png') no-repeat center; background-size:contain; min-height:100vh;">

        <div style="max-width:600px; background:rgba(255,255,255,0.8); padding:20px; border-radius:6px;">
            <p><span style="font-size:20px;">📞</span> Telp: (0286) 321091</p>
            <p><span style="font-size:20px;">📠</span> Fax: (0286) 323873</p>

            <h3 style="margin-top:20px;">Sosial Media</h3>
            <p>Email 1: rsudsetjonegoro@yahoo.co.id</p>
            <p>Email 2: krtsetjonegoro@gmail.com</p>
            <p>Instagram: @rsudwonosobo</p>
            <p>Youtube: RSUD Wonosobo</p>
            <p>Twitter: @rsud_wonosobo</p>
            <p>Whatsapp: 08112969666</p>
        </div>
    </div>

</body>

</html>