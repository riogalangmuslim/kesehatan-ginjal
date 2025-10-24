<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    $stmt->fetch();
    $stmt->close();

    if ($id && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Login gagal! Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* ✅ lebih fleksibel daripada height */
            padding: 20px;     /* ✅ supaya tidak kepotong di HP */
            box-sizing: border-box;
        }

        .card {
            width: 100%;
            max-width: 350px;   /* ✅ agar responsif */
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

        .alert {
            background: #ffdddd;
            color: #a00;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Login Pasien</div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
        <div class="card-footer">
            Belum punya akun? <a href="register.php">Register disini</a>
        </div>
    </div>
</body>
</html>
