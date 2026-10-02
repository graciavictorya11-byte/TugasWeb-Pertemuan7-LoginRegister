<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {

        $file = "users.json";

        if (!file_exists($file)) {
            $error = "File users.json tidak ditemukan.";
        } else {

            $users = json_decode(file_get_contents($file), true);

            if (!is_array($users)) {
                $users = [];
            }

            $loginBerhasil = false;

            foreach ($users as $user) {

                if (
                    isset($user["email"]) &&
                    $user["email"] === $email &&
                    isset($user["password"]) &&
                    password_verify($password, $user["password"])
                ) {

                    $_SESSION["user_id"] = $user["id"] ?? "";
                    $_SESSION["username"] = $user["name"] ?? "";
                    $_SESSION["email"] = $user["email"];

                    $loginBerhasil = true;

                    header("Location: dashboard.php");
                    exit;
                }
            }

            if (!$loginBerhasil) {
                $error = "Email atau password salah.";
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

    <title>Login - Pertemuan 7</title>

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

        .card h1 {
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

        .register {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .register a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .register a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Login</h1>

        <p class="subtitle">
            Silakan masuk ke akun Anda
        </p>

        <?php if (!empty($error)): ?>

            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Masukkan email"
                required
            >

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

        <div class="register">
            Belum punya akun?
            <a href="register.php">Daftar sekarang</a>
        </div>

    </div>

</div>

</body>

</html>