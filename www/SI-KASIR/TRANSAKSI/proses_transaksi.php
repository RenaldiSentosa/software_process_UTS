<?php


session_start();

/*
| TIMEZONE INDONESIA
*/

date_default_timezone_set('Asia/Jakarta');

require '../CONFIG/koneksi.php';

/*
| CEK LOGIN
*/

if (!isset($_SESSION['username'])) {
    header("Location: ../AUTH/login.php");
    exit;
}

/*
| CEK KERANJANG
*/

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<script>alert('Keranjang kosong!'); window.location='transaksi.php';</script>";
    exit;
}

/*
| VALIDASI BAYAR
*/

if (!isset($_POST['bayar']) || $_POST['bayar'] == "") {
    echo "<script>alert('Uang bayar wajib diisi!'); window.location='transaksi.php';</script>";
    exit;
}

$bayar = (int) $_POST['bayar'];

/*
| HITUNG TOTAL
*/

$total = 0;

foreach ($_SESSION['cart'] as $cart) {
    $total += $cart['subtotal'];
}

/*
| VALIDASI PEMBAYARAN
*/

if ($bayar < $total) {
    echo "<script>alert('Uang bayar kurang dari total tagihan!'); window.location='transaksi.php';</script>";
    exit;
}

$kembalian = $bayar - $total;

/*
| PROSES TRANSAKSI
*/

try {
    $pdo->beginTransaction();

    /*
    | NOMOR NOTA + TANGGAL WIB
    */

    $nomor_nota = "INV-" . date("YmdHis");
    $tanggal    = date("Y-m-d H:i:s");

    /*
    | INSERT PENJUALAN
    */

    $insert_penjualan = $pdo->prepare("
        INSERT INTO t_penjualan
        (nomor_nota, id_user, total_bayar, tgl_transaksi)
        VALUES (?, ?, ?, ?)
    ");

    $insert_penjualan->execute([$nomor_nota, $_SESSION['id_user'], $total, $tanggal]);

    $id_penjualan = $pdo->lastInsertId();

    /*
    | LOOP CART
    */

    foreach ($_SESSION['cart'] as $cart) {
        $cek_produk = $pdo->prepare("
            SELECT *
            FROM m_produk
            WHERE id_produk = ?
        ");

        $cek_produk->execute([$cart['id_produk']]);

        $produk = $cek_produk->fetch();

        if (!$produk) {
            throw new Exception("Produk tidak ditemukan!");
        }

        /*
        | VALIDASI STOK
        */

        if ($cart['qty'] > $produk['stok']) {
            throw new Exception("Stok {$produk['nama_produk']} tidak cukup");
        }

        /*
        | INSERT DETAIL
        */

        $insert_detail = $pdo->prepare("
            INSERT INTO t_penjualan_detail
            (id_penjualan, id_produk, qty, subtotal)
            VALUES (?, ?, ?, ?)
        ");

        $insert_detail->execute([$id_penjualan, $cart['id_produk'], $cart['qty'], $cart['subtotal']]);

        /*
        | UPDATE STOK
        */

        $update = $pdo->prepare("
            UPDATE m_produk
            SET stok = stok - ?
            WHERE id_produk = ?
        ");

        $update->execute([$cart['qty'], $cart['id_produk']]);

        /*
        | LOG STOK
        */

        $log = $pdo->prepare("
            INSERT INTO t_log_stok
            (id_produk, jumlah, tipe, keterangan)
            VALUES (?, ?, ?, ?)
        ");

        $log->execute([$cart['id_produk'], $cart['qty'], 'KELUAR', 'Penjualan']);
    }

    /*
    | COMMIT
    */

    $pdo->commit();

    /*
    | SESSION NOTA
    */

    $_SESSION['nota'] = [
        'nomor_nota' => $nomor_nota,
        'total'      => $total,
        'bayar'      => $bayar,
        'kembalian'  => $kembalian,
        'cart'       => $_SESSION['cart'],
        'tanggal'    => $tanggal
    ];

    /*
    | HAPUS CART
    */

    unset($_SESSION['cart']);

    /*
    | REDIRECT NOTA
    */

    header("Location: nota.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    ?>

<!DOCTYPE html>
<html>

<head>
    <title>Error</title>

    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: Arial;
            padding: 40px;
        }

        .box {
            background: #111827;
            padding: 30px;
            border-radius: 20px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>❌ Error Transaksi</h2>
        <br>
        <p><?= $e->getMessage(); ?></p>
        <a href="transaksi.php">Kembali</a>
    </div>
</body>

</html>

    <?php
}
?>