<?php


session_start();

require '../CONFIG/koneksi.php';

if ($_SESSION['role'] !== 'Admin') {
    die("Akses ditolak!");
}

$error   = "";
$success = "";

if (isset($_POST['simpan'])) {
    $nama_produk = trim($_POST['nama_produk']);
    $harga_jual  = $_POST['harga_jual'];
    $stok        = $_POST['stok'];

    /*
    | Validasi
    */

    if (empty($nama_produk) || $harga_jual === "" || $stok === "") {
        $error = "Data produk tidak lengkap, semua kolom wajib diisi!";
    } elseif ($harga_jual < 0) {
        $error = "Harga harus berupa angka positif!";
    } elseif ($stok < 0) {
        $error = "Stok tidak boleh negatif!";
    } else {

        /*
        | Insert Produk
        */

        $insert = $pdo->prepare("
            INSERT INTO m_produk
            (nama_produk, harga_jual, stok)
            VALUES (?, ?, ?)
        ");

        $insert->execute([$nama_produk, $harga_jual, $stok]);

        $id_produk = $pdo->lastInsertId();

        /*
        | Log Stok Awal
        */

        $log = $pdo->prepare("
            INSERT INTO t_log_stok
            (id_produk, jumlah, tipe, keterangan)
            VALUES (?, ?, ?, ?)
        ");

        $log->execute([$id_produk, $stok, 'MASUK', 'Stok awal produk']);

        $success = "Produk berhasil ditambahkan!";

        header("refresh:1;url=data_produk.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>

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
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .header {
            background: linear-gradient(135deg, #c2410c, #ea580c);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
        }

        .user-box {
            background: #fb923c;
            padding: 10px 18px;
            border-radius: 12px;
        }

        .box {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
        }

        .box h2 {
            margin-bottom: 25px;
        }

        .alert-error {
            background: #c2410c;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #ea580c;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #fff7ed;
            outline: none;
        }

        .input-group input:focus {
            box-shadow: 0 0 10px rgba(255, 255, 255, .5);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #ea580c, #fb923c);
            color: white;
            cursor: pointer;
            font-size: 16px;
            transition: .3s;
        }

        button:hover {
            background: linear-gradient(90deg, #c2410c, #ea580c);
            transform: translateY(-2px);
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            background: #c2410c;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            transition: .3s;
        }

        .back:hover {
            background: #9a3412;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <h1>➕ Tambah Produk</h1>
            <div class="user-box">
                <?= $_SESSION['username']; ?> (<?= $_SESSION['role']; ?>)
            </div>
        </div>

        <div class="box">
            <h2>Form Tambah Produk</h2>

            <?php if ($error != "") : ?>
                <div class="alert-error"><?= $error; ?></div>
            <?php endif; ?>

            <?php if ($success != "") : ?>
                <div class="alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Nama Produk</label>
                    <input
                        type="text"
                        name="nama_produk"
                        placeholder="Masukkan nama produk"
                        required>
                </div>

                <div class="input-group">
                    <label>Harga Jual</label>
                    <input
                        type="number"
                        name="harga_jual"
                        placeholder="Masukkan harga jual"
                        required>
                </div>

                <div class="input-group">
                    <label>Stok Awal</label>
                    <input
                        type="number"
                        name="stok"
                        placeholder="Masukkan stok awal"
                        required>
                </div>

                <button type="submit" name="simpan">💾 Simpan Produk</button>
            </form>

            <a href="data_produk.php" class="back">⬅ Kembali</a>
        </div>

    </div>
</body>

</html>