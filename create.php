<?php
session_start();

/* KẾT NỐI DATABASE */
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');
if (!$con) die("Không thể kết nối database: " . mysqli_connect_error());
mysqli_set_charset($con, "utf8");

/* KIỂM TRA ĐĂNG NHẬP */
if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit();
}

/* XỬ LÝ THÊM SẢN PHẨM */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = mysqli_real_escape_string($con, trim($_POST['name']));
    $price  = (float) $_POST['price'];
    $status = mysqli_real_escape_string($con, trim($_POST['status']));
    $imagePath = '';

    // Xử lý upload ảnh
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowTypes = ['jpg', 'jpeg', 'png'];
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            }
        }
    }

    // Thêm sản phẩm
    $query = "INSERT INTO products (name, price, status, image, created_at) 
              VALUES ('$name', $price, '$status', '$imagePath', NOW())";
    if (mysqli_query($con, $query)) {
        header("location: home.php");
        exit();
    } else {
        echo "Fail to add product: " . mysqli_error($con);
    }
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container { margin-top: 30px; max-width: 600px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-primary mb-4">Add New Product</h3>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required placeholder="name">
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
            <small class="text-muted">Format: JPG, JPEG, PNG</small>
        </div>

        <button type="submit" class="btn btn-success">Add</button>
        <a href="home.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
