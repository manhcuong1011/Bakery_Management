<?php
// cart_module/view.php - Xem giỏ hàng (Phiên bản Read-only)
require_once '../session.php';
require_once '../db_connect.php';

requireLoggedIn();

// Lấy User ID
$username = $_SESSION['username'];
$u_res = mysqli_query($con, "SELECT id FROM users WHERE username = '$username'");
$user_id = mysqli_fetch_assoc($u_res)['id'];

// XỬ LÝ XÓA (Chỉ giữ lại chức năng xóa)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_item'])) {
    $cart_id = (int)$_POST['cart_id'];
    mysqli_query($con, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    header("Location: view.php");
    exit();
}

// LẤY DỮ LIỆU
$query = "SELECT c.id as cart_id, c.quantity, p.name, p.price, p.image 
          FROM cart c JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = $user_id";
$result = mysqli_query($con, $query);
$total_cart_value = 0;
