<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: AUTH/login.php");
    exit;
}

require 'CONFIG/koneksi.php';

$role     = $_SESSION['role'];
$username = $_SESSION['username'];

/*
| TOTAL DASHBOARD
*/

$totalProduk = $pdo->query("
    SELECT COUNT(*) as total
    FROM m_produk
")->fetch();

$totalTransaksi = $pdo->query("
    SELECT COUNT(*) as total
    FROM t_penjualan
")->fetch();

$totalKasir = $pdo->query("
    SELECT COUNT(*) as total
    FROM m_user
    WHERE role = 'Kasir'
")->fetch();

/*
| BEST SELLER
*/

$bestSeller = $pdo->query("
    SELECT
        m_produk.nama_produk,
        SUM(t_penjualan_detail.qty) as total
    FROM t_penjualan_detail
    INNER JOIN m_produk
        ON t_penjualan_detail.id_produk = m_produk.id_produk
    GROUP BY m_produk.nama_produk
    ORDER BY total DESC
    LIMIT 1
")->fetch();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SI-KASIR</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ff8a00;
            color: white;
        }

        /* HEADER */
        .header {
            width: 100%;
            background: #d94801;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
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
            font-size: 30px;
        }

        .logo-text p {
            color: #fff7ed;
            font-size: 13px;
        }

        .user-box {
            background: #fdba74;
            padding: 12px 18px;
            border-radius: 12px;
            color: white;
        }

        /* MENU */
        .menu {
            width: 100%;
            background: #d94801;
            padding: 16px 30px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .menu a {
            text-decoration: none;
            color: white;
            background: #ea580c;
            padding: 12px 18px;
            border-radius: 12px;
            transition: .3s;
        }

        .menu a:hover {
            background: #fb923c;
            transform: translateY(-2px);
        }

        /* MAIN */
        .main {
            max-width: 1300px;
            margin: auto;
            padding: 35px 25px;
        }

        /* HERO */
        .hero {
            background: #fdac3c;
            padding: 40px;
            border-radius: 25px;
            margin-bottom: 30px;
        }

        .hero h1 {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .hero p {
            color: white;
            line-height: 1.8;
        }

        /* CARD */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #fdac3c;
            padding: 25px;
            border-radius: 22px;
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            color: white;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 34px;
            font-weight: bold;
        }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .box {
            background: #fdac3c;
            padding: 30px;
            border-radius: 22px;
        }

        .box h2 {
            margin-bottom: 15px;
        }

        .box p {
            line-height: 1.8;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 40px;
            color: white;
            padding-bottom: 25px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <div class="logo-icon">🛒</div>
            <div class="logo-text">
                <h1>SI-KASIR</h1>
                <p>Modern Cashier Management System</p>
            </div>
        </div>

        <div class="user-box">
            <?= $username; ?> (<?= $role; ?>)
        </div>
    </div>

    <!-- MENU -->
    <div class="menu">

        <a href="dashboard.php">🏠 Dashboard</a>

        <?php if ($role == 'Admin') : ?>
            <a href="PRODUK/data_produk.php">📦 Produk</a>
            <a href="LAPORAN/laporan_harian.php">📊 Laporan</a>
            <a href="LAPORAN/best_seller.php">🔥 Best Seller</a>
            <a href="LAPORAN/mutasi_stok.php">📋 Mutasi Stok</a>
            <a href="AUTH/manajemen_user.php">👤 Manajemen User</a>
        <?php endif; ?>

        <?php if ($role == 'Kasir') : ?>
            <a href="TRANSAKSI/transaksi.php">💳 Transaksi</a>
            <a href="TRANSAKSI/nota.php">🧾 Nota</a>
        <?php endif; ?>

        <a href="AUTH/logout.php">🚪 Logout</a>

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- HERO -->
        <div class="hero">
            <h1>Selamat Datang <?= $role; ?> 👋</h1>

            <p>
                SI-KASIR adalah aplikasi kasir modern berbasis web
                yang membantu pengelolaan transaksi penjualan,
                monitoring laporan, pengaturan stok barang,
                serta manajemen user secara cepat, aman, dan efisien.
            </p>
        </div>

        <?php if ($role == 'Admin') : ?>

            <!-- CARDS -->
            <div class="cards">

                <div class="card">
                    <h3>📦 Total Produk</h3>
                    <p><?= $totalProduk['total']; ?></p>
                </div>

                <div class="card">
                    <h3>💳 Total Transaksi</h3>
                    <p><?= $totalTransaksi['total']; ?></p>
                </div>

                <div class="card">
                    <h3>🔥 Best Seller</h3>
                    <p style="font-size:20px;">
                        <?= $bestSeller ? $bestSeller['nama_produk'] : 'Belum Ada'; ?>
                    </p>
                </div>

                <div class="card">
                    <h3>👨‍💼 Total Kasir</h3>
                    <p><?= $totalKasir['total']; ?></p>
                </div>

            </div>

            <!-- CONTENT -->
            <div class="grid">

                <div class="box">
                    <h2>🚀 Monitoring Sistem</h2>

                    <p>
                        Dashboard ini membantu admin memonitor aktivitas penjualan,
                        mengelola stok barang, melihat laporan transaksi,
                        serta memantau performa produk secara real-time.
                    </p>
                </div>

            </div>

        <?php endif; ?>

        <div class="footer">
            ULANGAN TENGAH SEMESTER - Renaldi Sentosa
        </div>

    </div>

</body>

</html>