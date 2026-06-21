<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// IDOR: Query tìm note phải khớp cả note_id VÀ user_id của người đang đăng nhập
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$note_id, $user_id]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    die("Ghi chú không tồn tại hoặc bạn không có quyền chỉnh sửa!");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title)) {
        $error = "Tiêu đề không được để trống!";
    } else {
        try {
            // Cập nhật dữ liệu an toàn
            $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $note_id, $user_id]);
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
    <title>Sửa Note</title>
</head>
<body>
    <h2>CHỈNH SỬA GHI CHÚ</h2>
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="edit.php?id=<?php echo $note_id; ?>" method="POST">
        <label>Tiêu đề:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 300px;"><br><br>
        
        <label>Nội dung:</label><br>
        <textarea name="content" rows="10" style="width: 300px;"><?php echo htmlspecialchars($note['content'], ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>
        
        <button type="submit">Cập nhật</button> | <a href="index.php">Hủy</a>
    </form>
</body>
</html>