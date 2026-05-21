<?php
$host = "db";
$user = "user_php";
$password = "password_php";
$db = "db_latihan";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$hasil = "";

if(isset($_POST['tampil'])){

    $nama = $_POST['nama'];
    $studio = $_POST['studio'];
    $kelas = $_POST['kelas'];
    $jumlah = $_POST['jumlah'];

    // Bintang tamu
    if($studio == "Studio 1"){
        $bintang = "Opick";
    } else {
        $bintang = "Raihan";
    }

    // Harga
    if($kelas == "VIP"){
        $harga = 500000;
    } else {
        $harga = 250000;
    }

    $total = $harga * $jumlah;

    // Simpan ke database
    $query = "INSERT INTO tiket (nama, studio, bintang, kelas, harga, jumlah, total)
              VALUES ('$nama', '$studio', '$bintang', '$kelas', '$harga', '$jumlah', '$total')";

    mysqli_query($conn, $query);

    // Output
    $hasil = "
Nama Pemesanan : $nama
Kode Studio    : $studio
Bintang Tamu   : $bintang
Jenis Kelas    : $kelas
Harga          : Rp " . number_format($harga,0,',','.') . "
Jumlah Beli    : $jumlah
----------------------------------------
Total Harga    : Rp " . number_format($total,0,',','.') . "
";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konser Amal</title>

    <style>
        body {
            font-family: "Times New Roman";
            background: #dcdcdc;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: #eeeeee;
            padding: 20px;
            border: 1px solid #999;
        }

        h2 {
            text-align: center;
            font-weight: bold;
        }

        hr {
            border: 1px solid black;
        }

        input[type=text],
        input[type=number] {
            width: 150px;
        }

        select {
            width: auto;
        }

        input[type=submit],
        input[type=reset] {
            padding: 2px 8px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>KONSER AMAL INDAHNYA BERBAGI</h2>
    <hr>

    <form method="post">

        Nama Pemesanan :
        <input type="text" name="nama" required><br><br>

        Kode Studio :
        <select name="studio">
            <option value="Studio 1">Studio 1</option>
            <option value="Studio 2">Studio 2</option>
        </select><br><br>

        Jenis Kelas :
        <input type="radio" name="kelas" value="VIP" checked> VIP
        <input type="radio" name="kelas" value="Festival"> Festival
        <br><br>

        Jumlah Beli :
        <input type="number" name="jumlah" required><br><br>

        <input type="submit" name="tampil" value="Tampil">
        <input type="reset" value="Batal">

    </form>

    <?php if($hasil != ""): ?>
        <hr>
        <pre><?php echo $hasil; ?></pre>
        <a href="">Kembali Ke Awal</a>
    <?php endif; ?>

</div>

</body>
</html>