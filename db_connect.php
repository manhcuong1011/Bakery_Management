<?php
// Thông tin cấu hình Database (Aiven)
$host = "localhost";
$username = "root";
$password = "12345";
$dbname = "ql_banhngot";
$port = 3306;

// Khởi tạo kết nối SSL
$con = mysqli_init();
mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL);

// Thực hiện kết nối
if (!mysqli_real_connect($con, $host, $username, $password, $dbname, $port)) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Thiết lập font tiếng Việt
mysqli_set_charset($con, "utf8");
?>