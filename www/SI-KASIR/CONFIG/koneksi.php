<?php

$host = "db";
#docker-compose down -v
#docker-compose up -d
$db = "db_majujaya"; 
$user = "user_php";
$pass = "password_php";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    #Jika terjadi kesalahan semisal salah ketik nama table akan memunculkan
    #error Exception yang bisa ditangkap
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

    #Secara default hasil query akan dikembalikan sebagai array asosiatif
    #membuat kode lebih bersih
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    #FITUR KEAMANAN PENTING untuk melawan SQL INjection
    PDO::ATTR_EMULATE_PREPARES => false,
];

try{
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e){
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>