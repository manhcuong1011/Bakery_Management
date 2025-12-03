<?php
// registration.php - Xử lý đăng ký
require_once 'session.php'; // Gọi file bảo mật

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($con, trim($_POST['user']));
    $raw_pass = $_POST['password']; // Mật khẩu gốc chưa mã hóa

    // 1. Kiểm tra username tồn tại
    $check_query = "SELECT * FROM users WHERE username='$user'";
    $result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Nếu trùng tên -> Báo lỗi
        header("Location: login.php?error=exists");
        exit();
    } else {
        // 2. MÃ HÓA MẬT KHẨU
        $hashed_pass = password_hash($raw_pass, PASSWORD_DEFAULT);

        // 3. Chèn vào DB
        $sql = "INSERT INTO users (username, password) VALUES ('$user', '$hashed_pass')";
        
        if (mysqli_query($con, $sql)) {
            
            echo "<script>
                alert('Đăng ký thành công! Vui lòng đăng nhập tài khoản mới.');
                window.location.href = 'login.php'; 
            </script>";
            
        } else {
            echo "Lỗi hệ thống: " . mysqli_error($con);
        }
    }
}
?>