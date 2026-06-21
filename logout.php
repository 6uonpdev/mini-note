<?php
session_start();
// Xóa bỏ toàn bộ dữ liệu Session trên Server
session_destroy();
header("Location: login.php");
exit();
?>