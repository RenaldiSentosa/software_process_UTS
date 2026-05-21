<?php
require 'koneksi.php';

$show_modal = false;
$pesan = "";

// ================= DELETE =================
if(isset($_GET['hapus'])){
    $data = explode("|", $_GET['hapus']);

    $sql = "DELETE FROM users 
            WHERE nama=? AND jk=? AND pendidikan=? AND hobi=? AND alamat=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// ================= EDIT MODE =================
$edit = false;
if(isset($_GET['edit'])){
    $edit = true;
    $data = explode("|", $_GET['edit']);

    $editData = [
        'nama' => $data[0],
        'jk' => $data[1],
        'pendidikan' => $data[2],
        'hobi' => explode(", ", $data[3]),
        'alamat' => $data[4]
    ];
}

// ================= UPDATE =================
if(isset($_POST['update'])){
    $lama = explode("|", $_POST['lama']);

    $nama       = $_POST['nama'];
    $jk         = $_POST['jk'];
    $pendidikan = $_POST['pendidikan'];
    $hobi       = isset($_POST['hobi']) ? implode(", ", $_POST['hobi']) : "";
    $alamat     = $_POST['alamat'];

    $sql = "UPDATE users SET nama=?, jk=?, pendidikan=?, hobi=?, alamat=?
            WHERE nama=? AND jk=? AND pendidikan=? AND hobi=? AND alamat=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nama, $jk, $pendidikan, $hobi, $alamat,
        $lama[0], $lama[1], $lama[2], $lama[3], $lama[4]
    ]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// ================= INSERT =================
if(isset($_POST['submit'])){
    $nama       = $_POST['nama'];
    $jk         = $_POST['jk'];
    $pendidikan = $_POST['pendidikan'];
    $hobi       = isset($_POST['hobi']) ? implode(", ", $_POST['hobi']) : "";
    $alamat     = $_POST['alamat'];

    $sql = "INSERT INTO users (nama, jk, pendidikan, hobi, alamat) VALUES (?, ?, ?, ?, ?)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$nama, $jk, $pendidikan, $hobi, $alamat]);

    $pesan = "Data berhasil disimpan!";
    $show_modal = true;
}

// ================= AMBIL DATA =================
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Form Input User</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #eef2f7;
    margin: 0;
    padding: 40px 0;
}

/* CONTAINER */
.container {
    max-width: 900px;
    margin: auto;
}

/* CARD */
.card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

/* JUDUL */
h2 {
    margin-bottom: 15px;
    color: #333;
}

/* FORM */
.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
}

/* INPUT */
input[type="text"],
select,
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    outline: none;
    transition: 0.2s;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2f6fc2;
}

/* CHECKBOX */
input[type="checkbox"] {
    margin-right: 5px;
}

/* BUTTON SIMPAN */
button {
    width: 100%;
    background: #28a745;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.2s;
}

button:hover {
    background: #218838;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
}

/* HEADER */
th {
    background: #2f6fc2;
    color: white;
    padding: 12px;
    text-align: center;
    font-size: 14px;
}

/* ISI */
td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

/* ZEBRA */
tr:nth-child(even) {
    background: #f5f7fb;
}

/* HOVER */
tr:hover {
    background: #eaf1ff;
}

/* AKSI BUTTON */
.btn-edit {
    background: #ffc107;
    color: black;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    margin-right: 5px;
    display: inline-block;
}

.btn-edit:hover {
    background: #e0a800;
}

.btn-delete {
    background: #dc3545;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    display: inline-block;
}

.btn-delete:hover {
    background: #c82333;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .container {
        padding: 0 15px;
    }

    table, th, td {
        font-size: 12px;
    }

    button {
        font-size: 14px;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- FORM -->
    <div class="card">
        <h2><?= $edit ? "Edit User" : "Form Input User" ?></h2>

        <form method="POST">
            <input type="hidden" name="lama" value="<?= $edit ? implode("|",$data) : '' ?>">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?= $edit ? $editData['nama'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jk">
                    <option value="L" <?= ($edit && $editData['jk']=="L")?'selected':'' ?>>Laki-laki</option>
                    <option value="P" <?= ($edit && $editData['jk']=="P")?'selected':'' ?>>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pendidikan</label>
                <select name="pendidikan">
                    <?php foreach(["SMA","D3","S1"] as $p): ?>
                    <option <?= ($edit && $editData['pendidikan']==$p)?'selected':'' ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Hobi</label>
                <?php foreach(["Ngoding","Gaming","Olahraga"] as $h): ?>
                <input type="checkbox" name="hobi[]" value="<?= $h ?>"
                    <?= ($edit && in_array($h,$editData['hobi']))?'checked':'' ?>> <?= $h ?>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat"><?= $edit ? $editData['alamat'] : '' ?></textarea>
            </div>

            <button type="submit" name="<?= $edit ? 'update' : 'submit' ?>" class="btn-submit">
                <?= $edit ? "Update" : "Simpan" ?>
            </button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card">
        <h2>Data User</h2>

        <table>
            <tr>
                <th>NAMA LENGKAP</th>
                <th>JENIS KELAMIN</th>
                <th>PENDIDIKAN</th>
                <th>HOBI</th>
                <th>ALAMAT</th>
                <th>AKSI</th>
            </tr>

            <?php foreach($users as $user): 
            $link = implode("|", [$user['nama'],$user['jk'],$user['pendidikan'],$user['hobi'],$user['alamat']]);
            ?>
            <tr>
                <td><?= htmlspecialchars($user['nama']) ?></td>
                <td><?= $user['jk'] ?></td>
                <td><?= $user['pendidikan'] ?></td>
                <td><?= htmlspecialchars($user['hobi']) ?></td>
                <td><?= nl2br(htmlspecialchars($user['alamat'])) ?></td>
                <td>
                    <a href="?edit=<?= urlencode($link) ?>" class="btn-edit">Edit</a>
                    <a href="?hapus=<?= urlencode($link) ?>" class="btn-delete" onclick="return confirm('Yakin?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>

</div>
</body>
</html>