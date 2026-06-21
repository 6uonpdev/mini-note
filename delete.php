<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // IDOR: Chỉ xóa nếu note_id đó thuộc về user_id này
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$note_id, $user_id]);
    
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Lỗi khi xóa: " . $e->getMessage());
}
?>