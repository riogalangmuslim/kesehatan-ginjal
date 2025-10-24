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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi & Misi</title>
</head>

<body
    style="margin:0; font-family:Arial, sans-serif; display:flex; background:linear-gradient(135deg, #e0eafc, #cfdef3);">

<!-- Sidebar -->
    <div style="width:220px;background:#4b6edc;color:white;height:100vh;
            padding:20px 0;position:fixed;left:0;top:0;
            display:flex;flex-direction:column;">

        <h2 style="text-align:center;margin-bottom:30px;">Dashboard Pasien</h2>

        <a href="dashboard_visimisi.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;background:#3a57b3;">Informasi</a>
        <a href="dashboard.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Data Pasien</a>
        <a href="hasil.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;">Hasil Diagnosa</a>
        <a href="logout.php" style="display:block;padding:12px 20px;color:white;text-decoration:none;">Keluar</a>
    </div>

    <!-- Content -->
    <div
        style="margin-left:220px; padding:40px; width:100%; min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:40px;">

        <!-- Visi & Misi Cards -->
        <div style="display:flex; gap:40px; flex-wrap:wrap; justify-content:center;">

            <!-- Visi -->
            <div
                style="border:none; border-radius:12px; padding:25px; width:300px; text-align:center; background:white; box-shadow:0 6px 15px rgba(0,0,0,0.15); transition:transform 0.3s, box-shadow 0.3s;">
                <h3 style="margin-bottom:20px; color:#4b6edc;">✨ VISI</h3>
                <p style="font-size:18px; font-weight:bold; line-height:1.6; color:#333;">
                    Menjadi Rumah Sakit <br>
                    Yang Terunggul, Bermutu, <br>
                    Dan Terpercaya
                </p>
            </div>

            <!-- Misi -->
            <div
                style="border:none; border-radius:12px; padding:25px; width:350px; background:white; box-shadow:0 6px 15px rgba(0,0,0,0.15); transition:transform 0.3s, box-shadow 0.3s;">
                <h3 style="text-align:center; margin-bottom:20px; color:#4b6edc;">🚀 MISI</h3>
                <ol style="font-size:16px; line-height:1.8; padding-left:20px; color:#333;">
                    <li>Menyelenggarakan Pelayanan Kesehatan yang Berfokus pada Pelanggan Sesuai Standar Nasional.</li>
                    <li>Melaksanakan Pelayanan, Pendidikan, Pelatihan, dan Penelitian di Bidang Kesehatan.</li>
                </ol>
            </div>
        </div>

        <!-- Kontak -->
        <div
            style="max-width:600px; background:white; padding:25px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.15); transition:transform 0.3s;">
            <h3 style="margin-bottom:15px; color:#4b6edc;">📞 Kontak Kami</h3>
            <p style="margin:8px 0; font-size:16px; color:#333;"><b>Telp:</b> (0286) 321091</p>
            <p style="margin:8px 0; font-size:16px; color:#333;"><b>Fax:</b> (0286) 323873</p>

            <h3 style="margin-top:20px; color:#4b6edc;">🌐 Sosial Media</h3>
            <p style="margin:6px 0;">📧 Email 1: <span style="color:#0073e6;">rsudsetjonegoro@yahoo.co.id</span></p>
            <p style="margin:6px 0;">📧 Email 2: <span style="color:#0073e6;">krtsetjonegoro@gmail.com</span></p>
            <p style="margin:6px 0;">📸 Instagram: <span style="color:#e1306c;">@rsudwonosobo</span></p>
            <p style="margin:6px 0;">▶️ Youtube: <span style="color:#ff0000;">RSUD Wonosobo</span></p>
            <p style="margin:6px 0;">🐦 Twitter: <span style="color:#1da1f2;">@rsud_wonosobo</span></p>
            <p style="margin:6px 0;">💬 Whatsapp: <span style="color:#25d366;">08112969666</span></p>
        </div>
    </div>

    <!-- Hover efek untuk kartu -->
    <script>
        document.querySelectorAll("div[style*='box-shadow']").forEach(card => {
            card.addEventListener("mouseenter", () => {
                card.style.transform = "translateY(-8px)";
                card.style.boxShadow = "0 12px 25px rgba(0,0,0,0.25)";
            });
            card.addEventListener("mouseleave", () => {
                card.style.transform = "translateY(0)";
                card.style.boxShadow = "0 6px 15px rgba(0,0,0,0.15)";
            });
        });
    </script>

</body>

</html>