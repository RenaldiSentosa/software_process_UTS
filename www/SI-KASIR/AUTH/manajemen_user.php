<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require '../CONFIG/koneksi.php';

/*
| PROTEKSI ADMIN
*/

if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak!");
}

/*
| CREATE USER
*/

if (isset($_POST['tambah_user'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        echo "<script>alert('Semua data wajib diisi!')</script>";
    } else {
        $cek = $pdo->prepare("
            SELECT *
            FROM m_user
            WHERE username=?
        ");

        $cek->execute([$username]);

        if ($cek->rowCount() > 0) {
            echo "<script>alert('Username sudah digunakan!')</script>";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare("
                INSERT INTO m_user
                (username, password, role)
                VALUES (?, ?, ?)
            ");

            $insert->execute([$username, $hash, $role]);

            echo "<script>alert('User berhasil ditambahkan!')</script>";
        }
    }
}

/*
| UPDATE USER
*/

if (isset($_POST['update_user'])) {
    $idUser   = $_POST['id_user'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = $pdo->prepare("
            UPDATE m_user
            SET
                username = ?,
                password = ?,
                role     = ?
            WHERE id_user = ?
        ");

        $update->execute([$username, $hash, $role, $idUser]);
    } else {
        $update = $pdo->prepare("
            UPDATE m_user
            SET
                username = ?,
                role     = ?
            WHERE id_user = ?
        ");

        $update->execute([$username, $role, $idUser]);
    }

    echo "<script>alert('User berhasil diupdate!')</script>";
}

/*
| DELETE USER
*/

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    /*
    | Cek Transaksi User
    */

    $cek = $pdo->prepare("
        SELECT *
        FROM t_penjualan
        WHERE id_user = ?
    ");

    $cek->execute([$id]);

    if ($cek->rowCount() > 0) {
        echo "<script>alert('User masih memiliki transaksi!')</script>";
    } else {
        $hapus = $pdo->prepare("
            DELETE FROM m_user
            WHERE id_user = ?
        ");

        $hapus->execute([$id]);

        echo "<script>alert('User berhasil dihapus!')</script>";
    }
}

/*
| READ USER
*/

$users = $pdo->query("
    SELECT *
    FROM m_user
    ORDER BY id_user DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #ff7b00, #ff9f1c, #ffb347);
            color: white;
            min-height: 100vh;
        }

        /*
        | TOP MENU
        */

        .top-menu {
            width: 100%;
            background: linear-gradient(90deg, #c2410c, #ea580c);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .25);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 45px;
        }

        .logo-text h1 {
            font-size: 28px;
        }

        .logo-text p {
            color: #fed7aa;
            font-size: 13px;
        }

        .menu {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .menu a {
            text-decoration: none;
            color: white;
            padding: 12px 18px;
            border-radius: 12px;
            background: #ea580c;
            transition: .3s;
            font-size: 14px;
        }

        .menu a:hover {
            background: #fb923c;
            transform: translateY(-2px);
        }

        /*
        | CONTENT
        */

        .main {
            padding: 30px;
        }

        .box {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
        }

        .box h2 {
            margin-bottom: 20px;
        }

        .input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            margin-bottom: 15px;
            outline: none;
        }

        .input:focus {
            box-shadow: 0 0 10px rgba(255, 255, 255, .4);
        }

        .btn {
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #ea580c, #f97316);
            color: white;
            cursor: pointer;
            transition: .3s;
            font-weight: bold;
        }

        .btn:hover {
            background: linear-gradient(90deg, #c2410c, #ea580c);
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #ea580c;
            padding: 14px;
        }

        table td {
            background: rgba(255, 255, 255, .08);
            padding: 12px;
            text-align: center;
        }

        .hapus {
            color: #fecaca;
            text-decoration: none;
            font-weight: bold;
        }

        .hapus:hover {
            color: white;
        }

        .update-form {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .update-form input,
        .update-form select {
            padding: 8px;
            border: none;
            border-radius: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #fff7ed;
        }

        @media (max-width: 768px) {
            .top-menu {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="top-menu">
        <div class="logo">
            <div class="logo-icon">🛒</div>
            <div class="logo-text">
                <h1>SI-KASIR</h1>
                <p>Modern Cashier System</p>
            </div>
        </div>

        <div class="menu">
            <a href="../dashboard.php">🏠 Dashboard</a>
            <a href="../PRODUK/data_produk.php">📦 Produk</a>
            <a href="../LAPORAN/laporan_harian.php">📊 Laporan</a>
            <a href="../LAPORAN/best_seller.php">🔥 Best Seller</a>
            <a href="../LAPORAN/mutasi_stok.php">📋 Mutasi</a>
            <a href="manajemen_user.php">👤 User</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main">

        <div class="box">
            <h2>👤 Tambah User Baru</h2>

            <form method="POST">
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan Username"
                    class="input"
                    required>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan Password"
                    class="input"
                    required>

                <select name="role" class="input" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="Admin">Admin</option>
                    <option value="Kasir">Kasir</option>
                </select>

                <button type="submit" name="tambah_user" class="btn">Tambah User</button>
            </form>
        </div>

        <div class="box">
            <h2>📋 Data User</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Update User</th>
                    <th>Delete</th>
                </tr>

                <?php foreach ($users as $u) : ?>
                <tr>
                    <td><?= $u['id_user']; ?></td>
                    <td><?= $u['username']; ?></td>
                    <td><?= $u['role']; ?></td>

                    <td>
                        <form method="POST" class="update-form">
                            <input type="hidden" name="id_user" value="<?= $u['id_user']; ?>">

                            <input
                                type="text"
                                name="username"
                                value="<?= $u['username']; ?>"
                                required>

                            <input
                                type="password"
                                name="password"
                                placeholder="Password Baru">

                            <select name="role">
                                <option value="Admin">Admin</option>
                                <option value="Kasir">Kasir</option>
                            </select>

                            <button type="submit" name="update_user" class="btn">Update</button>
                        </form>
                    </td>

                    <td>
                        <a
                            href="?hapus=<?= $u['id_user']; ?>"
                            onclick="return confirm('Yakin hapus user?')"
                            class="hapus">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="footer">ULANGAN TENGAH SEMESTER - Renaldi Sentosa</div>

    </div>
</body>

</html>