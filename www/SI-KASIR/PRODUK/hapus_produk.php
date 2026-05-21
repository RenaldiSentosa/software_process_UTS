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
| Proteksi Admin
*/

if ($_SESSION['role'] !== 'Admin') {
    die("
    <script>
    alert('Akses ditolak!');
    window.location='data_produk.php';
    </script>
    ");
}

/*
| Ambil ID Produk
*/

if (!isset($_GET['id'])) {
    die("
    <script>
    alert('ID produk tidak ditemukan!');
    window.location='data_produk.php';
    </script>
    ");
}

$id = $_GET['id'];

/*
| Cek Produk Ada / Tidak
*/

$produk = $pdo->prepare("
    SELECT *
    FROM m_produk
    WHERE id_produk = ?
");

$produk->execute([$id]);

$data = $produk->fetch();

if (!$data) {

    die("
    <script>
    alert('Produk tidak ditemukan!');
    window.location='data_produk.php';
    </script>
    ");

}

/*
| Cek Apakah Sudah Pernah Transaksi
|--------------------------------------------------------------------------
| Jika sudah pernah transaksi
| maka tidak boleh dihapus
*/

$cekTransaksi = $pdo->prepare("
    SELECT COUNT(*)
    FROM t_penjualan_detail
    WHERE id_produk = ?
");

$cekTransaksi->execute([$id]);

if ($cekTransaksi->fetchColumn() > 0) {

    echo "
    <script>
    alert('Barang tidak bisa dihapus karena sudah pernah transaksi!');
    window.location='data_produk.php';
    </script>
    ";

    exit;
}

/*
| Hapus Log Stok
|--------------------------------------------------------------------------
| Barang baru biasanya sudah punya
| histori di t_log_stok
*/

$hapusLog = $pdo->prepare("
    DELETE FROM t_log_stok
    WHERE id_produk = ?
");

$hapusLog->execute([$id]);

/*
| Hapus Produk
*/

$hapusProduk = $pdo->prepare("
    DELETE FROM m_produk
    WHERE id_produk = ?
");

$hapusProduk->execute([$id]);

echo "
<script>
alert('Produk berhasil dihapus!');
window.location='data_produk.php';
</script>
";

?>