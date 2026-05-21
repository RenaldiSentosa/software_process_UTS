<?php
require 'koneksi.php';

# Mengecek apakah tabel kategori kosong
$cek = $pdo->query("SELECT COUNT(*) as total FROM kategori")->fetch();
# Jika kosong, isi default kategori
if($cek['total'] == 0){
    $pdo->query("
        INSERT INTO kategori (nama_kategori) VALUES
        ('Hardware'),
        ('Software'),
        ('Network')
    ");
}

# Mengambil semua data kategori untuk dropdown
$kategori = $pdo->query("SELECT * FROM kategori")->fetchAll();

# Menyimpan kondisi WHERE untuk filter
$where = [];
$params = [];

# Jika ada input search (cari nama barang)
if(!empty($_GET['search'])){
    $where[] = "barang.nama_barang LIKE ?";
    $params[] = "%".$_GET['search']."%";
}

# Jika filter berdasarkan kategori dipilih
if(!empty($_GET['kategori'])){
    $where[] = "barang.id_kategori = ?";
    $params[] = $_GET['kategori'];
}

# Query utama dengan JOIN ke tabel kategori
$sql = "SELECT barang.*, kategori.nama_kategori
        FROM barang
        JOIN kategori ON barang.id_kategori = kategori.id_kategori";
        
# Jika ada filter, tambahkan WHERE
if($where){
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory Lab Komputer</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #1e1e1e, #3a3a3a);
    padding: 30px;
    color: white;
}

.container {
    max-width: 950px;
    margin: auto;
    background: #2c2c2c;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.5);
}

h2 {
    text-align: center;
    margin-bottom: 15px;
}

input, select {
    padding: 8px;
    margin: 5px;
    border-radius: 6px;
    border: none;
    background: #444;
    color: white;
}

button {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-tambah {
    display: inline-block;
    background: #28a745;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    margin-bottom: 10px;
}

.btn-edit {
    background: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
}

.btn-hapus {
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
}

table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

th {
    background: #444;
    color: white;
    padding: 10px;
}

td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #555;
}

.low {
    color: #ff4d4d;
    font-weight: bold;
}

a {
    text-decoration: none;
    margin: 0 5px;
    color: white;
}
</style>
</head>

<body>

<div class="container">
<h2>Inventory Lab Komputer</h2>

<div style="text-align:right;">
    <a href="tambah.php" class="btn-tambah">Tambah Barang</a>
</div>

<form method="GET">
<input type="text" name="search" placeholder="Cari barang...">

<select name="kategori">
<option value="">Semua</option>
<?php foreach($kategori as $k): ?>
<option value="<?= $k['id_kategori'] ?>">
<?= $k['nama_kategori'] ?>
</option>
<?php endforeach; ?>
</select>

<button>Cari</button>
</form>

<table>
<tr>
<th>No</th>
<th>Nama</th>
<th>Kategori</th>
<th>Stok</th>
<th>Kondisi</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>

<?php $no=1; foreach($data as $d): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= $d['nama_barang'] ?></td>
<td><?= $d['nama_kategori'] ?></td>

<td class="<?= $d['stok'] < 5 ? 'low' : '' ?>">
<?= $d['stok'] ?>
</td>

<td><?= $d['kondisi'] ?></td>
<td><?= $d['tgl_input'] ?></td>

<td>
<a href="edit.php?id=<?= $d['id_barang'] ?>" class="btn-edit">Edit</a>
<a href="hapus.php?id=<?= $d['id_barang'] ?>" class="btn-hapus" onclick="return confirm('Hapus?')">Hapus</a>
</td>
</tr>
<?php endforeach; ?>

</table>

</div>
</body>
</html>