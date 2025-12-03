<?php
// logout.php
session_start();

// 1. Xóa sạch dữ liệu trong mảng Session
$_SESSION = array();

// 2. Hủy Cookie của phiên làm việc (Session Cookie)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Xóa Cookie "Remember Me" (nếu có)
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}

// 4. Hủy Session trên server
session_destroy();

// 5. Về trang đăng nhập
header('location:login.php');
exit();
?>