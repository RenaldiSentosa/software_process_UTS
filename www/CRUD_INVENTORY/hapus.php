<?php
require 'koneksi.php';

$stmt = $pdo->prepare("DELETE FROM barang WHERE id_barang=?");
$stmt->execute([$_GET['id']]);

header("Location: index.php");