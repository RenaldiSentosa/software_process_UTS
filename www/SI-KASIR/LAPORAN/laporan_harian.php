<?php


session_start();

require '../CONFIG/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../AUTH/login.php");
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak!");
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'];

/*
| QUERY LAPORAN HARIAN
*/

$query = $pdo->query("
    SELECT
        t_penjualan.nomor_nota,
        t_penjualan.total_bayar,
        t_penjualan.tgl_transaksi,
        m_user.username,
        m_produk.nama_produk,
        m_produk.harga_jual,
        t_penjualan_detail.qty,
        (m_produk.harga_jual * t_penjualan_detail.qty) AS subtotal

    FROM t_penjualan

    INNER JOIN m_user
        ON t_penjualan.id_user = m_user.id_user

    INNER JOIN t_penjualan_detail
        ON t_penjualan.id_penjualan = t_penjualan_detail.id_penjualan

    INNER JOIN m_produk
        ON t_penjualan_detail.id_produk = m_produk.id_produk

    ORDER BY t_penjualan.id_penjualan DESC
");

$data = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            background: linear-gradient(135deg, #ff7b00, #ff9f1c, #ffb347);
            color: white;
        }

        .header {
            background: linear-gradient(90deg, #c2410c, #ea580c);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .25);
        }

        .logo {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .logo h1 {
            font-size: 28px;
        }

        .logo p {
            font-size: 13px;
            color: #fed7aa;
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
        }

        .menu a:hover {
            background: #fb923c;
            transform: translateY(-2px);
        }

        .container {
            max-width: 1400px;
            margin: auto;
            padding: 30px;
        }

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

        .user-box {
            display: inline-block;
            background: #ea580c;
            padding: 10px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .card {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
        }

        .table-wrapper {
            overflow: auto;
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

        .total {
            background: #f97316;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .footer {
            text-align: center;
            padding: 25px;
            color: #fff7ed;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <div style="font-size: 40px;">📊</div>
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

    <div class="container">

        <div class="hero">
            <h2>📊 Laporan Penjualan Harian</h2>
            <p>Menampilkan detail transaksi, produk yang dibeli, qty, harga, subtotal, kasir, dan total pembayaran.</p>
        </div>

        <div class="user-box">
            <?= $username; ?> (<?= $role; ?>)
        </div>

        <div class="card">
            <h2>📋 Detail Penjualan</h2>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>No</th>
                        <th>No Nota</th>
                        <th>Kasir</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Total Bayar</th>
                        <th>Tanggal</th>
                    </tr>

                    <?php $no = 1; foreach ($data as $d) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['nomor_nota']; ?></td>
                        <td><?= $d['username']; ?></td>
                        <td><?= $d['nama_produk']; ?></td>
                        <td>Rp <?= number_format($d['harga_jual']); ?></td>
                        <td><?= $d['qty']; ?></td>
                        <td>Rp <?= number_format($d['subtotal']); ?></td>
                        <td>
                            <span class="total">
                                Rp <?= number_format($d['total_bayar']); ?>
                            </span>
                        </td>
                        <td><?= date('d-m-Y H:i', strtotime($d['tgl_transaksi'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

    </div>

    <div class="footer">ULANGAN TENGAH SEMESTER - Renaldi Sentosa</div>
</body>

</html>