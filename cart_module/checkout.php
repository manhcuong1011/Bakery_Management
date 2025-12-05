<?php
// cart_module/checkout.php - Trang thanh toán
require_once '../session.php';
require_once '../db_connect.php';

requireLoggedIn();

// Lấy thông tin user để Auto-fill
$username = $_SESSION['username'];
$u_res = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($u_res);
$user_id = $user['id'];

// Kiểm tra giỏ hàng trống
$c_check = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM cart WHERE user_id = $user_id"));
if ($c_check['total'] == 0) {
    echo "<script>alert('Your cart is empty!'); window.location.href='view.php';</script>";
    exit();
}

// Tính tổng tiền
$t_res = mysqli_query($con, "SELECT SUM(c.quantity * p.price) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
$total_money = mysqli_fetch_assoc($t_res)['total'];
