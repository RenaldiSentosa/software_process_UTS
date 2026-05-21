<?php


session_start();

require '../CONFIG/koneksi.php';

$error = "";

/*
| Redirect Jika Sudah Login
*/

if (isset($_SESSION['username'])) {
    header("Location: ../dashboard.php");
    exit;
}

/*
| Bruteforce Prevention
*/

if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 0;
}

if (!isset($_SESSION['last_attempt'])) {
    $_SESSION['last_attempt'] = time();
}

if ($_SESSION['login_attempt'] >= 5) {
    $selisih = time() - $_SESSION['last_attempt'];

    if ($selisih < 300) {
        $sisa  = 300 - $selisih;
        $error = "Terlalu banyak percobaan, tunggu " . $sisa . " detik.";
    } else {
        $_SESSION['login_attempt'] = 0;
    }
}

/*
| Login Process
*/

if (isset($_POST['login']) && empty($error)) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        $stmt = $pdo->prepare("
            SELECT *
            FROM m_user
            WHERE username = ?
        ");

        $stmt->execute([$username]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id_user']        = $user['id_user'];
            $_SESSION['username']       = $user['username'];
            $_SESSION['role']           = $user['role'];
            $_SESSION['login_attempt']  = 0;

            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['login_attempt']++;
            $_SESSION['last_attempt'] = time();

            $sisaPercobaan = 5 - $_SESSION['login_attempt'];

            if ($sisaPercobaan > 0) {
                $error = "Username atau Password salah! Sisa percobaan: " . $sisaPercobaan;
            } else {
                $error = "Terlalu banyak percobaan, tunggu 5 menit.";
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
    <title>Login SI-KASIR</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ff7b00, #ff9f1c, #ffb347);
        }

        .login-box {
            width: 400px;
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .25);
            backdrop-filter: blur(10px);
        }

        .logo {
            text-align: center;
            font-size: 55px;
            margin-bottom: 12px;
        }

        h1 {
            text-align: center;
            color: #ea580c;
            margin-bottom: 5px;
        }

        .desc {
            text-align: center;
            color: #78716c;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .error {
            background: #fed7aa;
            color: #9a3412;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #9a3412;
        }

        .input-group input {
            width: 100%;
            padding: 13px;
            border: 1px solid #fdba74;
            border-radius: 12px;
            outline: none;
            font-size: 14px;
            transition: .3s;
        }

        .input-group input:focus {
            border-color: #f97316;
            box-shadow: 0 0 10px rgba(249, 115, 22, .3);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, #ea580c, #f97316);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        button:hover {
            transform: translateY(-2px);
            background: linear-gradient(90deg, #c2410c, #ea580c);
        }

        .identitas {
            margin-top: 25px;
            text-align: center;
            color: #9a3412;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-box">

        <div class="logo">🛒</div>

        <h1>SI-KASIR</h1>

        <div class="desc">Sistem Informasi Kasir Modern</div>

        <?php if ($error != "") : ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan Username"
                    required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan Password"
                    required>
            </div>

            <button type="submit" name="login">LOGIN</button>
        </form>

        <div class="identitas">ULANGAN TENGAH SEMESTER - Renaldi Sentosa</div>

    </div>
</body>

</html>