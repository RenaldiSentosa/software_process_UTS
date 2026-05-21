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

if(isset($_POST['hitung'])){

    $nama   = strtoupper($_POST['nama']);
    $alamat = strtoupper($_POST['alamat']);
    $jenis  = $_POST['jenis'];
    $warna  = $_POST['warna'];
    $jumlah = $_POST['jumlah'];

    // Harga
    if($jenis == "MOWILEX"){
        $harga = 20000;
    } elseif($jenis == "DANAPAINT"){
        $harga = 30000;
    } else {
        $harga = 40000;
    }

    $total = $harga * $jumlah;

    // Diskon
    if($jumlah >= 10){
        $diskon = 0.10 * $total;
    } elseif($jumlah >= 5){
        $diskon = 0.05 * $total;
    } else {
        $diskon = 0;
    }

    $bayar = $total - $diskon;

    // Simpan ke database
    $query = "INSERT INTO penjualan_cat 
              (nama, alamat, jenis, warna, harga, jumlah, total, diskon, bayar)
              VALUES 
              ('$nama','$alamat','$jenis','$warna','$harga','$jumlah','$total','$diskon','$bayar')";

    mysqli_query($conn, $query);

    // Simpan hasil ke variabel
    $hasil = "
TOKO CAT GUNA BANGUN JAYA
------------------------------------------
Nama Customer : $nama
Alamat        : $alamat
Jenis Cat     : $jenis
Warna         : $warna
Harga         : Rp. " . number_format($harga) . "
Jumlah Beli   : $jumlah
------------------------------------------
Total Harga   : Rp. " . number_format($total) . "
Diskon        : Rp. " . number_format($diskon) . "
------------------------------------------
Total Bayar   : Rp. " . number_format($bayar) . "
------------------------------------------
";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TOKO CAT GUNA BANGUN JAYA</title>

    <style>
        body {
            font-family: "Courier New";
            background: #e0e0e0;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: #dcdcdc;
            padding: 20px;
            border: 2px solid black;
        }

        h2 {
            text-align: center;
            letter-spacing: 3px;
        }

        table { width: 100%; }
        td { padding: 5px; }

        input, select {
            font-family: "Courier New";
            border: 1px solid black;
            padding: 3px;
            width: 100%;
        }

        input[type=radio] { width: auto; }

        .btn {
            border: 2px solid black;
            background: #c0c0c0;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn:hover { background: #a0a0a0; }

        .hasil {
            margin-top: 20px;
            border-top: 2px dashed black;
            padding-top: 10px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>TOKO CAT GUNA BANGUN JAYA</h2>

    <form method="post">
        <table>
            <tr>
                <td>Nama Customer</td>
                <td>:</td>
                <td><input type="text" name="nama" required></td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><input type="text" name="alamat"></td>
            </tr>

            <tr>
                <td>Jenis CAT</td>
                <td>:</td>
                <td>
                    <select name="jenis">
                        <option value="MOWILEX">MOWILEX</option>
                        <option value="DANAPAINT">DANAPAINT</option>
                        <option value="CATYLAC">CATYLAC</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Warna Cat</td>
                <td>:</td>
                <td>
                    <input type="radio" name="warna" value="Merah"> Merah
                    <input type="radio" name="warna" value="Biru" checked> Biru
                    <input type="radio" name="warna" value="Kuning"> Kuning
                </td>
            </tr>

            <tr>
                <td>Jumlah Beli</td>
                <td>:</td>
                <td><input type="number" name="jumlah" required></td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>
                    <button type="submit" name="hitung" class="btn">Hitung & Simpan</button>
                    <button type="reset" class="btn">Batal</button>
                </td>
            </tr>
        </table>
    </form>

    <?php if($hasil != ""): ?>
        <div class="hasil">
            <pre><?php echo $hasil; ?></pre>
        </div>
    <?php endif; ?>

</div>

</body>
</html>