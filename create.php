<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit();
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = mysqli_real_escape_string($con, trim($_POST['name']));
    $price  = (float) $_POST['price'];
    $status = mysqli_real_escape_string($con, trim($_POST['status']));
    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        // Kiểm tra tạo thư mục nếu chưa có
        if (!file_exists($targetDir)) { mkdir($targetDir, 0755, true); }

        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                $error_msg = "Lỗi: Không thể lưu file ảnh.";
            }
        } else {
            $error_msg = "Chỉ cho phép file ảnh JPG, JPEG, PNG, GIF.";
        }
    }

    if (empty($error_msg)) {
        $query = "INSERT INTO products (name, price, status, image, created_at) 
                  VALUES ('$name', $price, '$status', '$imagePath', NOW())";
        
        if (mysqli_query($con, $query)) {
            header("location: index.php"); // Đã sửa thành index.php
            exit();
        } else {
            $error_msg = "Lỗi SQL: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style> .container { margin-top: 30px; max-width: 600px; } </style>
</head>
<body>
<div class="container">
    <h3 class="text-primary mb-4">Add New Product</h3>
    <?php if($error_msg): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Price (VND):</label>
            <input type="number" name="price" class="form-control" required min="0" step="0.01">
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select name="status" class="form-control" required>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>
        </div>
        <div class="form-group">
            <label>Add Image:</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-success">Add</button>
        <a href="index.php" class="btn btn-secondary">Back</a> </form>
</div>
</body>
</html>