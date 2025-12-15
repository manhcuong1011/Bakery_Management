<?php
require_once '../session.php'; 
require_once '../db_connect.php'; 

// 1. Check quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // 1. Vẫn xóa khỏi Giỏ hàng (Cart) vì bánh xóa rồi ko ai mua được nữa
    mysqli_query($con, "DELETE FROM cart WHERE product_id = $id");

    // 2. Thay vì DELETE bảng products, ta UPDATE trạng thái thành 'Deleted', nhằm giữ data trong order_details
    $sql = "UPDATE products SET status = 'Deleted' WHERE id = $id";
    
    if (mysqli_query($con, $sql)) {
        header("Location: ../products.php");
        exit();
    } else {
        echo "<script>
            alert('Lỗi: " . mysqli_error($con) . "');
            window.location.href = '../products.php';
        </script>";
    }
} else {
    header("Location: ../products.php");
    exit();
}
?>