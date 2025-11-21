<?php
session_start();

/* connect to database check user*/
$con=mysqli_connect('localhost','root', '123456', 'ql_banhngot', '3306');

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

/* check for duplicate usernames and count records */
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