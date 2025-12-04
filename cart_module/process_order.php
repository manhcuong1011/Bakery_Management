<?php
// cart_module/process_order.php - Xử lý đơn hàng
session_start();
require_once '../session.php';
require_once '../db_connect.php';

requireLoggedIn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    // Lấy ID user
    $u_res = mysqli_query($con, "SELECT id FROM users WHERE username='$username'");
    $user_id = mysqli_fetch_assoc($u_res)['id'];

    // Lấy dữ liệu form
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $note = mysqli_real_escape_string($con, $_POST['note']);
    $total_money = (float)$_POST['total_money'];

    // 1. Cập nhật thông tin mới nhất vào bảng users (Tiện ích UX)
    mysqli_query($con, "UPDATE users SET fullname='$fullname', phone='$phone', address='$address' WHERE id=$user_id");
