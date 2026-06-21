<?php
session_start();
require 'config.php';

// Nếu chưa đăng nhập, trở về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // Nếu có từ khóa tìm kiếm
    if (!empty($search)) {
        // Dùng Prepared Statement để chống SQL Injection khi tìm kiếm
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? AND (title LIKE ? OR content LIKE ?)");
        $stmt->execute([$user_id, "%$search%", "%$search%"]);
    } else {
        // Lấy tất cả note của ĐÚNG user đang đăng nhập
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi tải ghi chú: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chính - Mini Note</title>
</head>
<body>
    <h2>Xin chào, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
    <a href="create.php">➕ Thêm Note mới</a> | <a href="logout.php">Đăng xuất</a>
    <br><br>

    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Tìm kiếm theo tên/nội dung..." value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">Tìm</button>
        <?php if(!empty($search)): ?>
            <a href="index.php">Xóa bộ lọc</a>
        <?php endif; ?>
    </form>

    <h3>Danh sách ghi chú của bạn:</h3>
    <?php if (count($notes) == 0): ?>
        <p>Bạn chưa có ghi chú nào hoặc không tìm thấy kết quả phù hợp.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Tiêu đề</th>
                <th>Hành động</th>
            </tr>
            <?php foreach ($notes as $note): ?>
                <tr>
                    <td>
                        <strong>
                            <a href="view.php?id=<?php echo $note['id']; ?>">
                                <?php echo htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </strong>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $note['id']; ?>">Sửa</a> | 
                        <a href="delete.php?id=<?php echo $note['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>