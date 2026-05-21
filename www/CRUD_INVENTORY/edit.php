<?php
require 'koneksi.php';

// ambil data
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id_barang=?");
$stmt->execute([$_GET['id']]);
$data = $stmt->fetch();

// ambil kategori
$kategori = $pdo->query("SELECT * FROM kategori")->fetchAll();

// update
if(isset($_POST['update'])){
    $stmt = $pdo->prepare("
        UPDATE barang SET
        nama_barang=?,
        id_kategori=?,
        stok=?,
        kondisi=?
        WHERE id_barang=?
    ");

    $stmt->execute([
        $_POST['nama'],
        $_POST['kategori'],
        $_POST['stok'],
        $_POST['kondisi'],
        $_POST['id']
    ]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Barang</title>

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

input::placeholder {
    color: #bbb;
}

button {
    width: 100%;
    background: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #0056b3;
}

.back {
    display: block;
    text-align: center;
    margin-top: 12px;
    text-decoration: none;
    color: #ccc;
}

.back:hover {
    color: white;
}
</style>
</head>

<body>

<div class="container">
<h2>Edit Barang</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $data['id_barang'] ?>">

<input type="text" name="nama" value="<?= $data['nama_barang'] ?>" required>
<input type="number" name="stok" value="<?= $data['stok'] ?>" required>

<select name="kategori">
<?php foreach($kategori as $k): ?>
<option value="<?= $k['id_kategori'] ?>"
<?= $data['id_kategori']==$k['id_kategori'] ? 'selected' : '' ?>>
<?= $k['nama_kategori'] ?>
</option>
<?php endforeach; ?>
</select>

<select name="kondisi">
<option <?= $data['kondisi']=='Baik' ? 'selected' : '' ?>>Baik</option>
<option <?= $data['kondisi']=='Rusak' ? 'selected' : '' ?>>Rusak</option>
</select>

<button name="update">Update</button>
</form>

<a href="index.php" class="back">Kembali</a>

</div>

</body>
</html>