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
    // 2. Tạo đơn hàng (ORDERS)
    $sql = "INSERT INTO orders (user_id, fullname, phone, address, note, total_money, created_at) 
            VALUES ($user_id, '$fullname', '$phone', '$address', '$note', $total_money, NOW())";
    
    if (mysqli_query($con, $sql)) {
        $order_id = mysqli_insert_id($con); // Lấy ID đơn hàng vừa tạo

        // 3. Chuyển từ Cart sang Order Details
        $cart_res = mysqli_query($con, "SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");

        while ($item = mysqli_fetch_assoc($cart_res)) {
            $pid = $item['product_id'];
            $price = $item['price'];
            $qty = $item['quantity'];
            mysqli_query($con, "INSERT INTO order_details (order_id, product_id, price, num) VALUES ($order_id, $pid, $price, $qty)");
        }

        // 4. Xóa giỏ hàng
        mysqli_query($con, "DELETE FROM cart WHERE user_id = $user_id");

        // 5. Chuyển hướng sang trang thành công
        header("Location: success.php?order_id=$order_id");
        exit();
    } else {
        die("System Error: " . mysqli_error($con));
    }
} else {
    header("Location: ../index.php");
}
?>

