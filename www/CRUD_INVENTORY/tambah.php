<?php
require 'koneksi.php';

# ambil kategori
$kategori = $pdo->query("SELECT * FROM kategori")->fetchAll();

# tambah
if(isset($_POST['tambah'])){
    $stmt = $pdo->prepare("
        INSERT INTO barang (nama_barang, id_kategori, stok, kondisi)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['nama'],
        $_POST['kategori'],
        $_POST['stok'],
        $_POST['kondisi']
    ]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Barang</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #1e1e1e, #3a3a3a);
    padding: 30px;
    color: white;
}

.container {
    max-width: 500px;
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
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
    border: none;
    background: #444;
    color: white;
}

button {
    width: 100%;
    background: #28a745;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-size: 16px;
}

.back {
    display: block;
    text-align: center;
    margin-top: 10px;
    color: #ccc;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="container">
<h2>Tambah Barang</h2>

<form method="POST">
<input type="text" name="nama" placeholder="Nama Barang" required>
<input type="number" name="stok" placeholder="Stok" required>

<select name="kategori">
<?php foreach($kategori as $k): ?>
<option value="<?= $k['id_kategori'] ?>">
<?= $k['nama_kategori'] ?>
</option>
<?php endforeach; ?>
</select>

<select name="kondisi">
<option>Baik</option>
<option>Rusak</option>
</select>

<button name="tambah">Tambah</button>
</form>

<a href="index.php" class="back">Kembali</a>

</div>

</body>
</html>