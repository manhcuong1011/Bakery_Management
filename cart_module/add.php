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

    // 2. Kiểm tra sản phẩm trong giỏ
    $check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_res = mysqli_query($con, $check_query);
    
    if (mysqli_num_rows($check_res) > 0) {
 // Có rồi -> Tăng số lượng
        mysqli_query($con, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Chưa có -> Thêm mới
        mysqli_query($con, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
