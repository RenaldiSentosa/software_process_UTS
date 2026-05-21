<?php

session_start();

require '../CONFIG/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../AUTH/login.php");
    exit;
}

$role = $_SESSION['role'];

$notaKosong = false;

if (!isset($_SESSION['nota'])) {
    $notaKosong = true;
} else {
    $nota = $_SESSION['nota'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan</title>

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

        .topbar {
            background: linear-gradient(90deg, #c2410c, #ea580c);
            padding: 18px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .logo {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .logo-icon {
            font-size: 42px;
        }

        .logo-text h1 {
            font-size: 28px;
        }

        .logo-text p {
            font-size: 13px;
            color: #fed7aa;
        }

        .user-box {
            background: #fb923c;
            padding: 10px 18px;
            border-radius: 12px;
        }

        .menu {
            background: rgba(194, 65, 12, .95);
            padding: 15px 30px;
            display: flex;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
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

        .container {
            max-width: 950px;
            margin: auto;
            padding: 30px;
        }

        .page-title {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px;
        }

        .nota-box {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 35px;
            border-radius: 25px;
        }

        .header-nota {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, .2);
            margin-bottom: 25px;
        }

        .logo-nota {
            font-size: 50px;
        }

        .info {
            line-height: 1.9;
            margin-bottom: 20px;
            color: #fff7ed;
        }

        .info b {
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #ea580c;
            padding: 15px;
        }

        table td {
            background: rgba(255, 255, 255, .08);
            padding: 15px;
            text-align: center;
        }

        .total-card {
            background: linear-gradient(90deg, #ea580c, #fb923c);
            padding: 18px;
            border-radius: 15px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }

        .button-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 18px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            cursor: pointer;
            transition: .3s;
        }

        .btn-primary {
            background: #ea580c;
        }

        .btn-primary:hover {
            background: #c2410c;
        }

        .btn-secondary {
            background: #9a3412;
        }

        .empty-box {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 60px;
            border-radius: 25px;
            text-align: center;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            color: #fff7ed;
        }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="logo">
            <div class="logo-icon">🛒</div>
            <div class="logo-text">
                <h1>SI-KASIR</h1>
                <p>Modern Cashier Management System</p>
            </div>
        </div>

        <div class="user-box">
            <?= $_SESSION['username']; ?> (<?= $_SESSION['role']; ?>)
        </div>
    </div>

    <div class="menu">
        <a href="../dashboard.php">🏠 Dashboard</a>
        <?php if ($role == 'Kasir') : ?>
            <a href="transaksi.php">💳 Transaksi</a>
            <a href="nota.php">🧾 Nota</a>
        <?php endif; ?>
        <a href="../AUTH/logout.php">🚪 Logout</a>
    </div>

    <div class="container">

        <?php if ($notaKosong) : ?>
            <div class="empty-box">
                <h2>📭 Belum Ada Nota</h2>
                <br>
                <p>Belum ada transaksi. Silakan lakukan transaksi dulu.</p>
                <br>
                <a href="transaksi.php" class="btn btn-primary">💳 Mulai Transaksi</a>
            </div>

        <?php else : ?>
            <div class="page-title">
                <h2>🧾 Nota Penjualan</h2>
            </div>

            <div class="nota-box">
                <div class="header-nota">
                    <div>
                        <h1>NOTA PENJUALAN</h1>
                        <p>SI-KASIR</p>
                    </div>
                    <div class="logo-nota">🧾</div>
                </div>

                <div class="info">
                    <p>No Nota : <b><?= $nota['nomor_nota']; ?></b></p>
                    <p>Tanggal : <b><?= date('d-m-Y H:i:s', strtotime($nota['tanggal'])); ?></b></p>
                </div>

                <table>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>

                    <?php foreach ($nota['cart'] as $c) : ?>
                    <tr>
                        <td><?= $c['nama_produk']; ?></td>
                        <td>Rp <?= number_format($c['harga_jual']); ?></td>
                        <td><?= $c['qty']; ?></td>
                        <td>Rp <?= number_format($c['subtotal']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <div class="total-card">
                    <span>Total</span>
                    <span>Rp <?= number_format($nota['total']); ?></span>
                </div>

                <div class="total-card">
                    <span>Bayar</span>
                    <span>Rp <?= number_format($nota['bayar']); ?></span>
                </div>

                <div class="total-card">
                    <span>Kembalian</span>
                    <span>Rp <?= number_format($nota['kembalian']); ?></span>
                </div>

                <div class="button-group">
                    <a href="transaksi.php" class="btn btn-primary">💳 Transaksi Lagi</a>
                    <button onclick="window.print()" class="btn btn-primary">🖨 Print</button>
                </div>
            </div>
        <?php endif; ?>

        <div class="footer">ULANGAN TENGAH SEMESTER - Renaldi Sentosa</div>

    </div>
</body>

</html>