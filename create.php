<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id']; // Lấy ID từ session an toàn trên server

    if (empty($title)) {
        $error = "Tiêu đề không được để trống!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $title, $content]);
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $error = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo Note mới</title>
</head>
<body>
    <h2>TẠO GHI CHÚ MỚI</h2>
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form action="create.php" method="POST">
        <label>Tiêu đề:</label><br>
        <input type="text" name="title" style="width: 300px;"><br><br>
        
        <label>Nội dung:</label><br>
        <textarea name="content" rows="10" style="width: 300px;"></textarea><br><br>
        
        <button type="submit">Lưu lại</button> | <a href="index.php">Hủy</a>
    </form>
</body>
</html>