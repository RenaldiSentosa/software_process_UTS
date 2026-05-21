<?php
$host = 'db';
$user = 'user_php';
$password = 'password_php';
$db = 'db_latihan';

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

$show_modal = false;
$pesan = '';

if (isset($_POST['submit'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $jk = $_POST['jk'];
    $pendidikan = $_POST['pendidikan'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $hobi = isset($_POST['hobi']) ? implode(', ', $_POST['hobi']) : '';

    $query = "INSERT INTO users (nama, jk, pendidikan, alamat, hobi)
              VALUES ('$nama', '$jk', '$pendidikan', '$alamat', '$hobi')";

    if (mysqli_query($conn, $query)) {
        $show_modal = true;
        $pesan = 'Data berhasil disimpan!';
    } else {
        $show_modal = true;
        $pesan = 'Data gagal disimpan!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Form Input User</title>

<style>
body {
    font-family: sans-serif;
    background: #f4f7f6;
    padding: 40px;
}

.form-card {
    max-width: 500px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin: auto;
}

.form-group { margin-bottom: 15px; }

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"], select, textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn-submit {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
}

/* Modal */
.modal {
    display: <?= $show_modal ? 'block' : 'none' ?>;
    position: fixed;
    z-index: 1;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}

.modal-content {
    background: white;
    margin: 15% auto;
    padding: 20px;
    width: 300px;
    border-radius: 8px;
    text-align: center;
}

.btn-close {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}
</style>
</head>

<body>

<div class="form-card">
    <h2>Form Input User</h2>

    <form method="POST">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" required>
        </div>

        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jk">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div class="form-group">
            <label>Pendidikan</label>
            <select name="pendidikan">
                <option value="SMA">SMA</option>
                <option value="D3">D3</option>
                <option value="S1">S1</option>
            </select>
        </div>

        <div class="form-group">
            <label>Hobi</label>
            <input type="checkbox" name="hobi[]" value="Ngoding"> Ngoding
            <input type="checkbox" name="hobi[]" value="Gaming"> Gaming
            <input type="checkbox" name="hobi[]" value="Olahraga"> Olahraga
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat"></textarea>
        </div>

        <button type="submit" name="submit" class="btn-submit">Simpan</button>
    </form>
</div>

<!-- Modal -->
<div class="modal">
    <div class="modal-content">
        <p><?= $pesan ?></p>
        <button class="btn-close" onclick="window.location.href=window.location.href;">OK</button>
    </div>
</div>

</body>
</html>