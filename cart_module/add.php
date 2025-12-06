<?php
// cart_module/add.php - Xử lý thêm sản phẩm vào giỏ
session_start();
require_once '../session.php'; 
require_once '../db_connect.php';

requireLoggedIn(); // Bắt buộc đăng nhập

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    
    $product_id = (int) $_POST['product_id'];
    $username = $_SESSION['username'];
    
 // 1. Lấy user_id
    $u_res = mysqli_query($con, "SELECT id FROM users WHERE username = '$username'");
    $user_id = mysqli_fetch_assoc($u_res)['id'];
