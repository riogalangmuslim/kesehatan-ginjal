<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 🔹 Cek apakah NIK sudah ada
    $check = $mysqli->prepare("SELECT id FROM users WHERE nik = ?");
    $check->bind_param("s", $nik);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('NIK sudah terpakai, gunakan NIK lain!'); window.location='register.php';</script>";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO users (nama, nik, email, password) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $nama, $nik, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            // 🔹 Tangkap error duplikat (jika ada race condition)
            if ($stmt->errno == 1062) {
                echo "<script>alert('NIK sudah terpakai, gunakan NIK lain!'); window.location='register.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .card {
            width: 100%;
            max-width: 350px;
            border-radius: 15px;
            overflow: hidden;
            background: #f0ecec;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: #5a8dee;
            color: black;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .card-body input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 10px;
            background: #d9d9d9;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #5a8dee;
            color: black;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background: #3d6fd1;
        }

        .card-footer {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            border-top: 1px solid #bbb;
        }

        .card-footer a {
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Register</div>
        <div class="card-body">
            <form method="post">
                <input type="text" name="nama" placeholder="Nama" required>
                <input type="text" name="nik" placeholder="NIK" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
        <div class="card-footer">
            Sudah punya akun? <a href="login.php">Login disini</a>
        </div>
    </div>
</body>
</html>
