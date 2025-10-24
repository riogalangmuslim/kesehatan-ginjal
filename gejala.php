<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

// cek koneksi database
if (!isset($mysqli) || $mysqli->connect_error) {
    die("Koneksi database gagal: " . $mysqli->connect_error);
}

$hasil_diagnosa = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gejala = $_POST['gejala'] ?? [];

    // Konversi gejala ke array boolean
    $G = [];
    for ($i = 1; $i <= 14; $i++) {
        $G[$i] = in_array("G$i", $gejala);
    }

    // Diagnosis default
    if (!empty($gejala)) {
        $diagnosis = "Tidak ada gangguan ginjal spesifik terdeteksi. Namun, tetap lakukan pemeriksaan rutin bila gejala berlanjut.";
    } else {
        $diagnosis = "Tidak ada gejala yang dipilih.";
    }

    // Aturan Forward Chaining
    if (!empty($gejala)) {
        if ($G[1] && $G[10] && $G[6] && $G[8]) {
            $diagnosis = "Sindrom Nefrotik (Proteinuria & Edema). Disarankan cek urin & konsultasi dokter.";
        } elseif ($G[13] && $G[6] && $G[8]) {
            $diagnosis = "Glomerulonefritis. Hematuria + hipertensi. Segera lakukan pemeriksaan fungsi ginjal.";
        } elseif ($G[9] && $G[7] && $G[14]) {
            $diagnosis = "Pielonefritis (Infeksi Ginjal Akut). Segera ke fasilitas kesehatan karena butuh antibiotik.";
        } elseif ($G[2] && $G[8] && $G[5] && $G[11] && $G[6]) {
            $diagnosis = "Penyakit Ginjal Kronis (CKD). Gejala progresif. Rujukan ke nefrologi sangat disarankan.";
        } elseif ($G[7] && $G[13] && $G[4]) {
            $diagnosis = "Batu Ginjal. Nyeri hebat + hematuria. Periksa imaging (USG/CT-Scan).";
        } elseif ($G[4] && $G[11] && $G[8]) {
            $diagnosis = "Gagal Ginjal Akut. Jika ada penurunan urin drastis, segera IGD!";
        } elseif ($G[1] && !$G[10] && !$G[13]) {
            $diagnosis = "Albuminuria / Gangguan Ginjal Ringan. Lanjutkan pemeriksaan urin.";
        }
    }

    $hasil_diagnosa = $diagnosis;

    // Simpan hasil ke database
    $user_id = (int) $_SESSION['user_id'];
    $gejala_text = !empty($gejala) ? implode(", ", $gejala) : "Tidak ada gejala";

    $stmt = $mysqli->prepare("INSERT INTO hasil_diagnosa (user_id, gejala, diagnosis) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare gagal: " . $mysqli->error);
    }

    $stmt->bind_param("iss", $user_id, $gejala_text, $diagnosis);
    if (!$stmt->execute()) {
        die("Eksekusi gagal: " . $stmt->error);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Gejala Ginjal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .gejala-card {
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 12px;
            background: white;
        }
        .gejala-card:hover {
            border-color: #0d6efd;
            background: #f1f6ff;
        }
        .gejala-card input {
            display: none;
        }
        .gejala-card label {
            width: 100%;
            cursor: pointer;
            font-weight: 500;
        }
        .gejala-card input:checked + label {
            background: #0d6efd;
            color: white;
            border-radius: 8px;
            padding: 10px;
            display: block;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Cek Kesehatan Ginjal</h3>
                    <p class="mb-0">Pilih gejala yang Anda alami dengan mencentang opsi di bawah</p>
                </div>
                <div class="card-body">
                    <?php if (!empty($hasil_diagnosa)): ?>
                        <div class="alert alert-info">
                            <h5>Hasil Analisis:</h5>
                            <p><?= htmlspecialchars($hasil_diagnosa); ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $daftar_gejala = [
                                    "G1" => "Bengkak di kaki / Edema",
                                    "G2" => "Sering buang air kecil malam (Nokturia)",
                                    "G3" => "Nafsu makan menurun",
                                    "G4" => "Mual / muntah",
                                    "G5" => "Kulit gatal",
                                    "G6" => "Tekanan darah tinggi",
                                    "G7" => "Nyeri punggung / flank"
                                ];
                                foreach ($daftar_gejala as $kode => $nama) {
                                    echo "<div class='gejala-card'>
                                            <input type='checkbox' name='gejala[]' value='".htmlspecialchars($kode)."' id='".htmlspecialchars($kode)."'>
                                            <label for='".htmlspecialchars($kode)."'>".htmlspecialchars($nama)."</label>
                                          </div>";
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $daftar_gejala2 = [
                                    "G8" => "Cepat lelah / lemas",
                                    "G9" => "Demam / menggigil",
                                    "G10" => "Urine berbusa (Proteinuria)",
                                    "G11" => "Sesak napas",
                                    "G12" => "Gangguan tidur",
                                    "G13" => "Darah di urine (Hematuria)",
                                    "G14" => "Nyeri/terbakar saat berkemih (Dysuria)"
                                ];
                                foreach ($daftar_gejala2 as $kode => $nama) {
                                    echo "<div class='gejala-card'>
                                            <input type='checkbox' name='gejala[]' value='".htmlspecialchars($kode)."' id='".htmlspecialchars($kode)."'>
                                            <label for='".htmlspecialchars($kode)."'>".htmlspecialchars($nama)."</label>
                                          </div>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">Cek Diagnosis</button>
                            <a href="dashboard.php" class="btn btn-secondary btn-lg px-5">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
