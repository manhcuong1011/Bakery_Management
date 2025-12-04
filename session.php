<?php
// session.php - Quản lý Session và Bảo mật

// Bắt đầu session (luôn phải ở dòng đầu tiên)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php'; // Kết nối DB để check cookie nếu cần

/**
 * 1. BẢO MẬT SESSION (Chống Hijacking)
 * Ý tưởng: Lưu thông tin trình duyệt (User Agent) lúc đăng nhập.
 * Mỗi lần load trang, kiểm tra xem trình duyệt có thay đổi không. Nếu có -> Có kẻ gian -> Đăng xuất ngay.
 */
if (isset($_SESSION['username'])) {
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    } elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        // Phát hiện bất thường (Session Hijacking)
        session_destroy();
        header('Location: login.php');
        exit();
    }
}

/**
 * 2. TỰ ĐỘNG ĐĂNG NHẬP (REMEMBER ME)
 * Nếu chưa có Session (đã tắt trình duyệt) nhưng vẫn còn Cookie 'remember_user'
 */
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_user'];
    
    // Tìm user sở hữu token này trong DB (Logic này ta sẽ setup kỹ ở bảng users sau, tạm thời dùng username làm token cho bài tập)
    // Ở đây tạm dùng username để check cho đơn giản logic trước.
    
    $user = mysqli_real_escape_string($con, $token);
    $query = "SELECT * FROM users WHERE username = '$user'";
    $result = mysqli_query($con, $query);
    
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Tái tạo Session
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role']; // Lấy quyền hạn (admin/user)
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
}

/**
 * 3. CÁC HÀM KIỂM TRA QUYỀN (Helper Functions)
 */

// Hàm bắt buộc phải đăng nhập mới được vào
function requireLoggedIn() {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }
}

// Hàm bắt buộc phải là ADMIN mới được vào
function requireAdmin() {
    requireLoggedIn(); // Phải đăng nhập trước đã
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("❌ Truy cập bị từ chối! Bạn không có quyền Admin. <a href='index.php'>Về trang chủ</a>");
    }
}
?>