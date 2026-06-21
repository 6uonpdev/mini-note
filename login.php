<?php
require 'config.php';

// Khởi động Session để lưu trạng thái đăng nhập của người dùng trên Server
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } else {
        try {
            // Tìm người dùng theo username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Nếu tìm thấy user, ta tiến hành kiểm tra mật khẩu
            if ($user) {
                // Dùng hàm password_verify để so sánh mật khẩu nhập vào với chuỗi hash trong DB
                if (password_verify($password, $user['password'])) {
                    
                    // Đăng nhập đúng -> Cấp "thẻ bài" Session cho người dùng
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Chuyển hướng người dùng sang trang chủ index.php
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Mật khẩu không chính xác!";
                }
            } else {
                $error = "Tài khoản không tồn tại!";
            }
        } catch (PDOException $e) {
            $error = "Có lỗi xảy ra: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Mini Note</title>
</head>
<body>
    <h2>ĐĂNG NHẬP</h2>
    
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="login.php" method="POST">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username"><br><br>
        
        <label>Mật khẩu:</label><br>
        <input type="password" name="password"><br><br>
        
        <button type="submit">Đăng nhập</button>
    </form>
    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</body>
</html>