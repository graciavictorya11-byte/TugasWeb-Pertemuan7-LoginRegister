<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validasi nama
    if (empty($name)) {
        $error = "Nama wajib diisi.";
    }

    // Validasi email
    elseif (empty($email)) {
        $error = "Email wajib diisi.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }

    // Validasi password
    elseif (empty($password)) {
        $error = "Password wajib diisi.";
    }

    elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    }

    else {

        $file = "users.json";

        // Jika file belum ada
        if (!file_exists($file)) {
            file_put_contents($file, "[]");
        }

        // Membaca users.json
        $users = json_decode(file_get_contents($file), true);

        if (!is_array($users)) {
            $users = [];
        }

        // Mengecek email yang sudah terdaftar
        $emailSudahAda = false;

        foreach ($users as $user) {
            if (
                isset($user["email"]) &&
                strtolower($user["email"]) === strtolower($email)
            ) {
                $emailSudahAda = true;
                break;
            }
        }

        if ($emailSudahAda) {

            $error = "Email sudah terdaftar.";

        } else {

            // Membuat data user baru
            $newUser = [
                "id" => uniqid(),
                "name" => htmlspecialchars($name),
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ];

            // Menambahkan user
            $users[] = $newUser;

            // Menyimpan ke JSON
            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT)
            );

            $success = "Registrasi berhasil! Silakan login.";

            // Mengosongkan form
            $name = "";
            $email = "";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Pertemuan 7</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 15px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;

            border: 1px solid #ccc;
            border-radius: 8px;

            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;

            background: #667eea;
            color: white;

            border: none;
            border-radius: 8px;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;
        }

        button:hover {
            background: #5568d8;
        }

        .error {
            background: #ffe0e0;
            color: #c62828;

            padding: 10px;
            margin-bottom: 20px;

            border-radius: 8px;
            text-align: center;
        }

        .success {
            background: #e0f7e9;
            color: #218838;

            padding: 10px;
            margin-bottom: 20px;

            border-radius: 8px;
            text-align: center;
        }

        .login {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .login a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Register</h1>

        <p class="subtitle">
            Buat akun baru
        </p>

        <?php if (!empty($error)): ?>

            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <?php if (!empty($success)): ?>

            <div class="success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label for="name">
                Nama
            </label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Masukkan nama"
                value="<?= htmlspecialchars($name ?? '') ?>"
                required
            >

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Masukkan email"
                value="<?= htmlspecialchars($email ?? '') ?>"
                required
            >

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 6 karakter"
                required
            >

            <button type="submit">
                Daftar
            </button>

        </form>

        <div class="login">
            Sudah punya akun?
            <a href="login.php">Login di sini</a>
        </div>

    </div>

</div>

</body>

</html>
