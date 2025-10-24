<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Ambil semua pasien + diagnosa
$sql = "SELECT u.id AS user_id, u.nama, u.nik, u.email, h.id AS hasil_id, h.gejala, h.diagnosis, h.created_at
        FROM users u
        LEFT JOIN hasil_diagnosa h ON u.id = h.user_id
        ORDER BY u.id DESC, h.created_at DESC";
$result = $mysqli->query($sql);
$data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Helper function aman
function safe($str)
{
    return htmlspecialchars($str ?? '-', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body style="font-size:14px;background:#f4f6f9;font-family:'Segoe UI',Arial,sans-serif;">

    <!-- Sidebar -->
    <div
        style="width:220px;background:#4b6edc;color:white;height:100vh;padding:20px 0;position:fixed;top:0;left:0;display:flex;flex-direction:column;box-shadow:2px 0 8px rgba(0,0,0,0.2);">
        <h2 style="text-align:center;margin-bottom:30px;font-size:20px;font-weight:bold;">Dashboard Admin</h2>
        <a href="admin_dashboard.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;font-size:15px;background:rgba(0,0,0,0.2);">Diagnosa
            Pasien</a>
        <a href="admin_data.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;font-size:15px;">Data Pasien</a>
        <a href="admin_logout.php"
            style="display:block;padding:12px 20px;color:white;text-decoration:none;font-size:15px;">Keluar</a>
    </div>

    <!-- Konten -->
    <div style="margin-left:240px;padding:25px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 style="color:#333;margin:0;">Data Pasien & Riwayat Diagnosa</h2>
        </div>

        <div style="background:white;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,0.1);overflow:hidden;">
            <div style="background:#343a40;color:white;padding:14px 20px;font-weight:bold;font-size:16px;">Tabel Pasien
                & Diagnosa</div>
            <div style="padding:15px;">
                <table id="dataTable" class="table table-bordered table-striped table-hover"
                    style="font-size:13px;width:100%;">
                    <thead style="background:#4b6edc;color:white;text-align:center;vertical-align:middle;">
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>Gejala</th>
                            <th>Diagnosis</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data): ?>
                            <?php foreach ($data as $row): ?>
                                <tr style="vertical-align:middle;" onmouseover="this.style.background='#f1f3f9';"
                                    onmouseout="this.style.background='';">
                                    <td><?= safe($row['nama']) ?></td>
                                    <td><?= safe($row['nik']) ?></td>
                                    <td><?= safe($row['email']) ?></td>
                                    <td><?= safe($row['gejala']) ?></td>
                                    <td><?= safe($row['diagnosis']) ?></td>
                                    <td><?= safe($row['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center;color:#999;">Belum ada data pasien.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "scrollX": true
            });
        });
    </script>
</body>

</html>