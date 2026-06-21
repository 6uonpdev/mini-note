<?php
$host = "localhost";
$dbname = "mini_note";
$username = "root"; // Mặc định của XAMPP là root
$password = "";     // Mặc định của XAMPP là bỏ trống

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Bật chế độ báo lỗi để dễ debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>