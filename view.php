<?php
session_start();
require 'config.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // IDOR: Câu truy vấn phải khớp cả note_id VÀ user_id của người đang đăng nhập.
    // Nếu ai đó cố tình đổi id trên URL thành note của người khác, DB sẽ không trả về kết quả.
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$note_id, $user_id]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    // Nếu không tìm thấy note (hoặc của người khác)
    if (!$note) {
        die("<h3>Ghi chú không tồn tại hoặc bạn không có quyền xem ghi chú này!</h3><a href='index.php'>Quay lại trang chủ</a>");
    }
} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết ghi chú</title>
</head>
<body>
    <h2>CHI TIẾT GHI CHÚ</h2>
    <hr>
    
    <h3>Tiêu đề:</h3>
    <p><strong><?php echo htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
    
    <h3>Nội dung:</h3>
    <p><?php echo nl2br(htmlspecialchars($note['content'], ENT_QUOTES, 'UTF-8')); ?></p>
    
    <hr>
    <a href="index.php">⬅️ Quay lại danh sách</a> | 
    <a href="edit.php?id=<?php echo $note['id']; ?>">✏️ Sửa Note này</a> | 
    <a href="delete.php?id=<?php echo $note['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">❌ Xóa</a>
</body>
</html>