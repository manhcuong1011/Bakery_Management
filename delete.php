<?php
session_start();
/* KẾT NỐI DATABASE AIVEN */
    $host = "bakery-db-bakery2025.j.aivencloud.com";
    $username = "avnadmin";
    $password = "AVNS_w4fPt6P2925yeh3Cb5R";
    $dbname = "ql_banhngot";
    $port = 19064;

    // Aiven yêu cầu kết nối bảo mật (SSL), nên phải dùng cách này:
    $con = mysqli_init();
    mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL); 
    
    // Thực hiện kết nối
    if (!mysqli_real_connect($con, $host, $username, $password, $dbname, $port)) {
        die("Không thể kết nối database: " . mysqli_connect_error());
    }
    
    // Thiết lập font tiếng Việt
    mysqli_set_charset($con, "utf8");
/* KẾT NỐI DATABASE 
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');*/
if (!$con) {
    header('location: home.php?msg=error');
    exit();
}

// Kiểm tra session
if(!isset($_SESSION['username'])){
    header('location:login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Chuẩn bị câu truy vấn DELETE
    $query = "DELETE FROM products WHERE id = ?";
    
    // Sử dụng Prepared Statements để xóa an toàn
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Xóa thành công, chuyển hướng về trang chủ với thông báo
        header('location: home.php?msg=deleted');
        exit();
    } else {
        // Xóa thất bại
        header('location: home.php?msg=error');
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    // Không có ID hợp lệ
    header('location: home.php?msg=error');
    exit();
}

mysqli_close($con);
?>