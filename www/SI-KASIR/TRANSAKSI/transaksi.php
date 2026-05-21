<?php


session_start();

require '../CONFIG/koneksi.php';

/*
| Proteksi Login
*/

if (!isset($_SESSION['username'])) {
    header("Location: ../AUTH/login.php");
    exit;
}

/*
| HANYA KASIR
*/

if ($_SESSION['role'] !== 'Kasir') {
    header("Location: ../dashboard.php");
    exit;
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'];

/*
| Ambil Produk
*/

$produk = $pdo->query("
    SELECT *
    FROM m_produk
    ORDER BY nama_produk ASC
")->fetchAll();

/*
| Session Cart
*/

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$error   = "";
$success = "";

/*
| Tambah Keranjang
*/

if (isset($_POST['tambah_cart'])) {
    $id_produk = $_POST['id_produk'];
    $qty       = $_POST['qty'];

    if ($qty <= 0) {
        $error = "Qty harus lebih dari 0!";
    } else {
        $query = $pdo->prepare("
            SELECT *
            FROM m_produk
            WHERE id_produk = ?
        ");

        $query->execute([$id_produk]);

        $data = $query->fetch();

        if (!$data) {
            $error = "Produk tidak ditemukan!";
        } elseif ($qty > $data['stok']) {
            $error = "Stok {$data['nama_produk']} tidak mencukupi untuk transaksi ini.";
        } else {
            $_SESSION['cart'][] = [
                'id_produk'   => $data['id_produk'],
                'nama_produk' => $data['nama_produk'],
                'harga_jual'  => $data['harga_jual'],
                'qty'         => $qty,
                'subtotal'    => $qty * $data['harga_jual']
            ];

            $success = "Produk berhasil ditambahkan ke keranjang!";
        }
    }
}

/*
| Hapus Cart
*/

if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][$_GET['hapus']]);
    header("Location: transaksi.php");
    exit;
}

/*
| Hitung Total
*/

$total = 0;

foreach ($_SESSION['cart'] as $cart) {
    $total += $cart['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ff8c00;
            color: white;
        }

        /* HEADER */
        .header {
            background: #d35400;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 1px solid rgba(255, 255, 255, .15);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 42px;
        }

        .logo-text h1 {
            font-size: 30px;
        }

        .logo-text p {
            font-size: 13px;
            color: #ffe8cc;
        }

        .user-box {
            background: #ffb347;
            padding: 12px 18px;
            border-radius: 12px;
            color: white;
        }

        /* MENU */
        .menu {
            background: #c94f00;
            padding: 18px 35px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .menu a {
            text-decoration: none;
            color: white;
            background: #f57c00;
            padding: 12px 18px;
            border-radius: 12px;
            transition: .3s;
        }

        .menu a:hover {
            background: #ffb347;
            transform: translateY(-2px);
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 35px 25px;
        }

        /* PAGE TITLE */
        .page-title {
            background: #ffad33;
            padding: 35px;
            border-radius: 24px;
            margin-bottom: 25px;
            text-align: center;
        }

        .page-title h2 {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .page-title p {
            color: #fff4e6;
        }

        /* CARD */
        .card {
            background: #ffad33;
            padding: 30px;
            border-radius: 24px;
            margin-bottom: 25px;
        }

        .card h3 {
            margin-bottom: 20px;
            font-size: 28px;
        }

        /* ALERT */
        .alert-error {
            background: #c62828;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #2e7d32;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        /* FORM */
        input,
        select {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: white;
            margin-bottom: 15px;
        }

        button {
            padding: 14px 22px;
            border: none;
            border-radius: 12px;
            background: #f57c00;
            color: white;
            cursor: pointer;
            transition: .3s;
        }

        button:hover {
            background: #e65100;
        }

        /* TABLE */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #e67e00;
            padding: 15px;
            text-align: center;
        }

        table td {
            background: #ffb84d;
            padding: 15px;
            text-align: center;
            color: white;
        }

        /* TOTAL */
        .total-box {
            margin-top: 20px;
            background: #f57c00;
            padding: 20px;
            border-radius: 15px;
            font-size: 24px;
            font-weight: bold;
        }

        /* BUTTON HAPUS */
        .btn-hapus {
            background: #d32f2f;
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
        }

        .btn-hapus:hover {
            background: #b71c1c;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 35px;
            color: white;
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
        <a href="../dashboard.php">🏠 Dashboard</a>
        <a href="transaksi.php">💳 Transaksi</a>
        <a href="nota.php">🧾 Nota</a>
        <a href="../AUTH/logout.php">🚪 Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <div class="page-title">
            <h2>💳 Transaksi Penjualan</h2>
            <p>Kelola transaksi penjualan produk SI-KASIR dengan cepat, mudah, dan modern.</p>
        </div>

        <!-- TAMBAH KERANJANG -->
        <div class="card">
            <h3>🛒 Tambah Keranjang</h3>

            <?php if ($error != "") : ?>
                <div class="alert-error"><?= $error; ?></div>
            <?php endif; ?>

            <?php if ($success != "") : ?>
                <div class="alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <select name="id_produk" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($produk as $p) : ?>
                        <option value="<?= $p['id_produk']; ?>">
                            <?= $p['nama_produk']; ?>
                            | Stok : <?= $p['stok']; ?>
                            | Rp <?= number_format($p['harga_jual']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="number" name="qty" placeholder="Masukkan Qty" required>

                <button type="submit" name="tambah_cart">Tambah Keranjang</button>
            </form>
        </div>

        <!-- KERANJANG -->
        <div class="card">
            <h3>🧾 Keranjang Belanja</h3>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>

                    <?php if (count($_SESSION['cart']) > 0) : ?>
                        <?php $no = 1; foreach ($_SESSION['cart'] as $index => $c) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $c['nama_produk']; ?></td>
                            <td>Rp <?= number_format($c['harga_jual']); ?></td>
                            <td><?= $c['qty']; ?></td>
                            <td>Rp <?= number_format($c['subtotal']); ?></td>
                            <td>
                                <a
                                    href="?hapus=<?= $index; ?>"
                                    class="btn-hapus"
                                    onclick="return confirm('Hapus produk ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Keranjang masih kosong.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <div class="total-box">
                Total Belanja : Rp <?= number_format($total); ?>
            </div>
        </div>

        <!-- PEMBAYARAN -->
        <div class="card">
            <h3>💰 Pembayaran</h3>

            <form action="proses_transaksi.php" method="POST">
                <input type="number" name="bayar" placeholder="Masukkan Uang Bayar" required>
                <button type="submit" name="proses">Selesaikan Transaksi</button>
            </form>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">ULANGAN TENGAH SEMESTER - Renaldi Sentosa</div>
</body>

</html>