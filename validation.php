<?php
session_start();

/* connect to database check user*/
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');

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