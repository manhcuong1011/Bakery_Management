<?php
session_start();
require_once 'db_connect.php';

$user = mysqli_real_escape_string($con, $_POST['user']); 
$pass = md5($_POST['password']);

// Kiểm tra user tồn tại
$s = "SELECT * FROM users WHERE username='$user'"; 
$result = mysqli_query($con, $s);
$num = mysqli_num_rows($result);

if ($num == 1) {
    echo "Username Exists";
    echo "<script>window.location.href = 'login.php';</script>"; 
} else {
    $reg = "INSERT INTO users(username, password) VALUES ('$user', '$pass')";
    
    if (mysqli_query($con, $reg)) {
        $_SESSION['username'] = $user;
        echo "<script>
            alert('Đăng ký thành công!');
            window.location.href = 'login.php'; 
        </script>";
    } else {
        echo "Lỗi: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>