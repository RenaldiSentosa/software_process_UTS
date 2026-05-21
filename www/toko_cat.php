<?php
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

        table {
            width: 100%;
        }

        td {
            padding: 5px;
        }

        input, select, textarea {
            font-family: "Courier New";
            border: 1px solid black;
            padding: 3px;
            width: 100%;
        }

        input[type=radio] {
            width: auto;
        }

        .btn {
            font-family: "Courier New";
            border: 2px solid black;
            background: #c0c0c0;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn:hover {
            background: #a0a0a0;
        }

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
                    <button type="submit" name="hitung" class="btn">Hitung</button>
                    <button type="reset" class="btn">Batal</button>
                </td>
            </tr>
        </table>
    </form>

<?php
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

    echo "<div class='hasil'>";
    echo "<pre>";
    echo "TOKO CAT GUNA BANGUN JAYA\n";
    echo "------------------------------------------\n";
    echo "Nama Customer : $nama\n";
    echo "Alamat        : $alamat\n";
    echo "Jenis Cat     : $jenis\n";
    echo "Warna         : $warna\n";
    echo "Harga         : Rp. " . number_format($harga) . "\n";
    echo "Jumlah Beli   : $jumlah\n";
    echo "------------------------------------------\n";
    echo "Total Harga   : Rp. " . number_format($total) . "\n";
    echo "Diskon        : Rp. " . number_format($diskon) . "\n";
    echo "------------------------------------------\n";
    echo "Total Bayar   : Rp. " . number_format($bayar) . "\n";
    echo "------------------------------------------\n";
    echo "</pre>";
    echo "</div>";
}
?>

</div>

</body>
</html>