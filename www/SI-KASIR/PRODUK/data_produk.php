<?php


session_start();

require '../CONFIG/koneksi.php';

/*
| Proteksi Admin
*/

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
| Search Produk
*/

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

/*
| FILTER STOK KRITIS (FIXED)
*/

$where = "WHERE nama_produk LIKE ?";

if (isset($_GET['stok_kritis'])) {
    $where .= " AND stok < 5";
}

/*
| Query Produk
*/

$query = $pdo->prepare("
    SELECT *
    FROM m_produk
    $where
    ORDER BY id_produk DESC
");

$query->execute(["%$search%"]);

$produk = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - SI KASIR</title>

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
            gap: 20px;
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
            font-size: 45px;
        }

        .logo h1 {
            font-size: 30px;
        }

        .logo p {
            color: #fed7aa;
            font-size: 13px;
        }

        /*
        | MENU
        */

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
            border-radius: 22px;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
        }

        .hero h2 {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .hero p {
            color: #fff7ed;
            line-height: 1.7;
        }

        /*
        | CARD
        */

        .card {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 22px;
            margin-bottom: 25px;
        }

        /*
        | TOP ACTION
        */

        .top-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            background: #ea580c;
            color: white;
            text-decoration: none;
            cursor: pointer;
            transition: .3s;
            font-size: 14px;
            font-weight: bold;
        }

        .btn:hover {
            background: #fb923c;
            transform: translateY(-2px);
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-form input {
            width: 260px;
            padding: 12px;
            border: none;
            border-radius: 12px;
            outline: none;
        }

        .search-form button {
            padding: 12px 18px;
            border: none;
            border-radius: 12px;
            background: #ea580c;
            color: white;
            cursor: pointer;
            transition: .3s;
        }

        .search-form button:hover {
            background: #fb923c;
        }

        /*
        | INFO BOX
        */

        .info-box {
            background: rgba(255, 255, 255, .08);
            padding: 16px;
            border-radius: 14px;
            color: #fff7ed;
            margin-bottom: 20px;
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
        }

        table th {
            background: #ea580c;
            padding: 16px;
            text-align: center;
        }

        table td {
            background: rgba(255, 255, 255, .08);
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        table tr:hover td {
            background: rgba(255, 255, 255, .15);
        }

        .stok-kritis td {
            background: #c2410c !important;
        }

        /*
        | ACTION BUTTON
        */

        .action-btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            margin: 2px;
            transition: .3s;
        }

        .edit {
            background: #fb923c;
        }

        .edit:hover {
            background: #ea580c;
        }

        .hapus {
            background: #c2410c;
        }

        .hapus:hover {
            background: #9a3412;
        }

        /*
        | FOOTER
        */

        .footer {
            text-align: center;
            padding: 25px;
            color: #fff7ed;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <div class="logo-icon">🛒</div>
            <div>
                <h1>SI-KASIR</h1>
                <p>Modern Cashier System</p>
            </div>
        </div>

        <div class="menu">
            <a href="../dashboard.php">🏠 Dashboard</a>
            <a href="data_produk.php">📦 Produk</a>
            <a href="../LAPORAN/laporan_harian.php">📊 Laporan</a>
            <a href="../LAPORAN/best_seller.php">🔥 Best Seller</a>
            <a href="../LAPORAN/mutasi_stok.php">📋 Mutasi</a>
            <a href="../AUTH/manajemen_user.php">👤 User</a>
            <a href="../AUTH/logout.php">🚪 Logout</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <div class="hero">
            <h2>📦 Data Produk SI-KASIR</h2>
            <p>Kelola produk, stok barang, pencarian data, serta monitoring stok kritis dengan sistem modern.</p>
        </div>

        <div class="card">

            <div class="top-action">
                <a href="tambah_produk.php" class="btn">➕ Tambah Produk</a>

                <form method="GET" class="search-form">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari nama produk..."
                        value="<?= $search; ?>">
                    <button type="submit">🔍 Cari</button>
                    <a href="?stok_kritis=1" class="btn">⚠ Stok Kritis</a>
                    <a href="data_produk.php" class="btn">🔄 Reset</a>
                </form>
            </div>

            <div class="info-box">
                Produk dengan background merah berarti stok kurang dari 5.
            </div>

            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>

                    <?php $no = 1; foreach ($produk as $p) : ?>
                    <tr class="<?= $p['stok'] < 5 ? 'stok-kritis' : ''; ?>">
                        <td><?= $no++; ?></td>
                        <td><?= $p['nama_produk']; ?></td>
                        <td>Rp <?= number_format($p['harga_jual']); ?></td>
                        <td><?= $p['stok']; ?></td>
                        <td>
                            <a href="edit_produk.php?id=<?= $p['id_produk']; ?>" class="action-btn edit">Edit</a>
                            <a
                                href="hapus_produk.php?id=<?= $p['id_produk']; ?>"
                                class="action-btn hapus"
                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                Hapus
                            </a>
                        </td>
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