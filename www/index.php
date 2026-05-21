<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 600px;
            background-color: aqua;
            padding: 20px;
            border-radius: 8px;
            margin-top: 50px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        .btn-simpan {
           background-color: blue;
           color: white;
           padding: 10px 20px;
           border: none;
           border-radius: 4px;
        }

        .btn-reset {
           background-color: red;
           color: white;
           padding: 10px 20px;
           border: none;
           border-radius: 4px;
        }

        .result {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2 class="text-center">Formulir Pendaftaran</h2>

    <form method="post">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Umur</label>
            <input type="number" name="umur" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Jenis Kelamin</label><br>
            <input type="radio" name="rd_jk" value="laki-laki" checked> Laki-laki
            <input type="radio" name="rd_jk" value="perempuan"> Perempuan
        </div>

        <div class="form-group">
            <label>Pendidikan Terakhir</label>
            <select name="pendidikan" class="form-control">
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="D3">D3</option>
                <option value="S1">S1</option>
            </select>
        </div>

        <div class="form-group">
            <label>Hobi</label><br>
            <input type="checkbox" name="hobi[]" value="Coding"> Coding
            <input type="checkbox" name="hobi[]" value="Olahraga"> Olahraga
            <input type="checkbox" name="hobi[]" value="Menulis"> Menulis
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3" class="form-control"></textarea>
        </div>

        <button type="submit" name="submit" class="btn-simpan">Simpan</button>
        <button type="reset" class="btn-reset">Reset</button>
    </form>

    <?php
    if(isset($_POST['submit'])){
        $nama = htmlspecialchars($_POST['nama']);
        $umur = htmlspecialchars($_POST['umur']);
        $jk = $_POST['rd_jk'];
        $pendidikan = $_POST['pendidikan'];
        $hobi = isset($_POST['hobi']) ? $_POST['hobi'] : [];
        $alamat = nl2br(htmlspecialchars($_POST['alamat']));

        echo "<div class='result'>";
        echo "<h3>Hasil Input</h3>";
        echo "<p>Nama: $nama</p>";
        echo "<p>Umur: $umur</p>";
        echo "<p>Jenis Kelamin: $jk</p>";
        echo "<p>Pendidikan: $pendidikan</p>";
        echo "<p>Hobi: " . implode(", ", $hobi) . "</p>";
        echo "<p>Alamat: $alamat</p>";
        echo "</div>";
    }
    ?>
</div>
</body>
</html>