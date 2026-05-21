<?php


session_start();

require '../CONFIG/koneksi.php';

/*
| Proteksi Admin
*/

if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak!");
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'];

/*
| Query Mutasi Stok
*/

$query = $pdo->query("
    SELECT
        t_log_stok.*,
        m_produk.nama_produk
    FROM t_log_stok
    INNER JOIN m_produk
        ON t_log_stok.id_produk = m_produk.id_produk
    ORDER BY t_log_stok.id_log DESC
");

$data = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutasi Stok - SI KASIR</title>

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
        }

        /*
        | HEADER
        */

        .header {
            width: 100%;
            background: linear-gradient(90deg, #c2410c, #ea580c);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .25);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 40px;
        }

        .logo h1 {
            font-size: 28px;
        }

        .logo p {
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
            background: #ea580c;
            border-radius: 12px;
            transition: .3s;
            font-size: 14px;
        }

        .menu a:hover {
            background: #fb923c;
            transform: translateY(-2px);
        }

        /*
        | CONTAINER
        */

        .container {
            max-width: 1300px;
            margin: auto;
            padding: 30px;
        }

        /*
        | HERO
        */

        .hero {
            background: linear-gradient(135deg, #ea580c, #fb923c);
            padding: 35px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
        }

        .hero h2 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .hero p {
            color: #fff7ed;
        }

        /*
        | USER INFO
        */

        .user-box {
            display: inline-block;
            margin-bottom: 20px;
            background: #ea580c;
            padding: 10px 18px;
            border-radius: 12px;
        }

        /*
        | CARD
        */

        .card {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
        }

        /*
        | TABLE
        */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #ea580c;
            padding: 15px;
            text-align: center;
        }

        table td {
            background: rgba(255, 255, 255, .08);
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        table tr:hover td {
            background: rgba(255, 255, 255, .15);
        }

        /*
        | BADGE TIPE
        */

        .badge-masuk {
            background: #fb923c;
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
            display: inline-block;
            font-weight: bold;
        }

        .badge-keluar {
            background: #c2410c;
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
            display: inline-block;
            font-weight: bold;
        }

        /*
        | FOOTER
        */

        .footer {
            text-align: center;
            padding: 25px;
            color: #fff7ed;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <div class="logo-icon">📋</div>
            <div>
                <h1>SI-KASIR</h1>
                <p>Modern Cashier System</p>
            </div>
        </div>

        <div class="menu">
            <a href="../dashboard.php">🏠 Dashboard</a>
            <a href="../PRODUK/data_produk.php">📦 Produk</a>
            <a href="laporan_harian.php">📊 Laporan</a>
            <a href="best_seller.php">🔥 Best Seller</a>
            <a href="mutasi_stok.php">📋 Mutasi</a>
            <a href="../AUTH/manajemen_user.php">👤 User</a>
            <a href="../AUTH/logout.php">🚪 Logout</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <div class="hero">
            <h2>📋 Laporan Mutasi Stok</h2>
            <p>Menampilkan seluruh aktivitas barang masuk dan barang keluar pada sistem kasir.</p>
        </div>

        <div class="user-box">
            <?= $username; ?> (<?= $role; ?>)
        </div>

        <div class="card">
            <h2>📦 Riwayat Perubahan Stok</h2>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Tipe</th>
                        <th>Keterangan</th>
                    </tr>

                    <?php $no = 1; foreach ($data as $d) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['nama_produk']; ?></td>
                        <td><?= $d['jumlah']; ?></td>
                        <td>
                            <?php if (strtolower($d['tipe']) == 'masuk') : ?>
                                <span class="badge-masuk">⬇ MASUK</span>
                            <?php else : ?>
                                <span class="badge-keluar">⬆ KELUAR</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $d['keterangan']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">ULANGAN TENGAH SEMESTER - Renaldi Sentosa</div>
</body>

</html>