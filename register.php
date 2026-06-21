<?php
require 'config.php';

$error = "";
$success = "";

// Kiểm tra nếu người dùng bấm nút Submit Form (gửi dữ liệu lên)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Kiểm tra xem người dùng có bỏ trống ô nào không
    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ tên tài khoản và mật khẩu!";
    } else {
        try {
            // Kiểm tra xem username này đã có ai dùng chưa
            // Sử dụng Prepared Statement (dấu chấm hỏi ?) để chống lỗi SQL Injection
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Tên tài khoản này đã tồn tại!";
            } else {
                // Băm mật khẩu 
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Lưu tài khoản mới vào Database
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hashed_password]);

                $success = "Đăng ký thành công! <a href='login.php'>Bấm vào đây để đăng nhập</a>";
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
    <title>Đăng ký - Mini Note</title>
</head>
<body>
    <h2>ĐĂNG KÝ TÀI KHOẢN</h2>
    
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form action="register.php" method="POST">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username"><br><br>
        
        <label>Mật khẩu:</label><br>
        <input type="password" name="password"><br><br>
        
        <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
</body>
</html>