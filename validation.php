<?php
session_start();
require_once 'db_connect.php'; 

$user = mysqli_real_escape_string($con, $_POST['user']); 
$pass = md5($_POST['password']);

$s = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
$result = mysqli_query($con, $s);
$num = mysqli_num_rows($result);

if ($num == 1) {
    $_SESSION['username'] = $user;
    header('location:index.php'); 
} else {
    header('location:login.php?error=invalid');
}

mysqli_close($con);
?>