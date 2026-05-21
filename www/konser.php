<?php
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konser Amal</title>

    <style>
        body {
            font-family: "Times New Roman";
            background: #f5f5f5;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
        }

        h2 {
            text-align: center;
        }

        hr {
            border: 1px solid black;
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

<?php
if(isset($_POST['tampil'])){

    $nama = $_POST['nama'];
    $studio = $_POST['studio'];
    $kelas = $_POST['kelas'];
    $jumlah = $_POST['jumlah'];

    if($studio == "Studio 1"){
        $bintang = "Opick";
    } else if($studio == "Studio 2"){
        $bintang = "Raihan";
    }

    
    if($kelas == "VIP"){
        $harga = 500000;
    } else {
        $harga = 250000;
    }

    
    $total = $harga * $jumlah;

    echo "<hr>";
    echo "Nama Pemesanan : $nama <br>";
    echo "Kode Studio : $studio <br>";
    echo "Bintang Tamu : $bintang <br>";
    echo "Jenis Kelas : $kelas <br>";
    echo "Harga : $harga <br>";
    echo "Jumlah Beli : $jumlah <br>";
    echo "<hr>";
    echo "Total Harga : $total <br><br>";
    echo "<a href=''>Kembali Ke Awal</a>";
}
?>

</div>

</body>
</html>