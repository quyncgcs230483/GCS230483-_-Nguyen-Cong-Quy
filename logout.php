<?php
session_start(); // Bắt đầu phiên làm việc

// Hủy bỏ tất cả session
session_unset(); 
session_destroy(); 

// Chuyển hướng người dùng trở lại trang login
header("Location: login.php"); // Đảm bảo rằng tên file của bạn đúng
exit();
?>
