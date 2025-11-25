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
$con=mysqli_connect('localhost','root', '123456', 'ql_banhngot', '3306');*/

// Kiểm tra kết nối
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

/* create variables to store data */
$user = mysqli_real_escape_string($con, $_POST['user']); 
$pass = md5($_POST['password']);

/* select data from DB */
$s="select * from users where username='$user'"; 

/* result variable to store data */
$result = mysqli_query($con, $s);

// Kiểm tra xem câu lệnh có chạy được không
if (!$result) {
    die("Lỗi SQL: " . mysqli_error($con)); // Nó sẽ hiện ra dòng chữ: Table 'defaultdb.users' doesn't exist
}

// Nếu chạy được thì mới đếm dòng
$num = mysqli_num_rows($result);

if ($num == 1) {
    // Đăng kí thất bại
    echo "Username Exists";
} else {
    $reg = "INSERT INTO users(username, password) 
           VALUES ('$user', '$pass')";
    
    if (mysqli_query($con, $reg)) {
        // Đăng kí thành công
        echo "Registration successful";
        $_SESSION['username'] = $user;
        header("location:home.php"); 
    } else {
        echo "Error: " . $reg . "<br>" . mysqli_error($con);
    }
}

// Nếu đăng ký thất bại (Username Exists), quay lại trang login
if ($num == 1) {
    header("location:login.php");
    exit();
}
// Đóng kết nối
mysqli_close($con);

?>