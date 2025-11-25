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
/* connect to database check user
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');*/

// Kiểm tra kết nối
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

/* create variables to store data */
$user = mysqli_real_escape_string($con, $_POST['user']); 
$pass = md5($_POST['password']);

/* select data from DB */
$s = "SELECT * FROM users WHERE username='$user' AND password='$pass'";

/* result variable to store data */
$result = mysqli_query($con, $s);

/* check for matched records */
$num = mysqli_num_rows($result);

if ($num == 1) {
  /* Storing the username and session */
    $_SESSION['username'] = $user;
    header('location:home.php');
} else {
    // Đăng nhập thất bại
    header('location:login.php?error=invalid');
}

exit();

// Đóng kết nối
mysqli_close($con);

?>