<?php
session_start();
require_once 'session.php';
requireAdmin(); // Chỉ admin mới được vào trang này

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("ID sản phẩm không hợp lệ.");

// Lấy dữ liệu cũ
$result = mysqli_query($con, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($result);
if (!$product) die("Không tìm thấy sản phẩm.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = mysqli_real_escape_string($con, trim($_POST['name']));
    $price  = (float)$_POST['price'];
    $status = mysqli_real_escape_string($con, trim($_POST['status']));
    $image  = $product['image']; 

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0755, true);
        
        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        if (in_array($fileType, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $targetFile;
        }
    }

    $sql = "UPDATE products SET name='$name', price=$price, status='$status', image='$image' WHERE id=$id";
    if (mysqli_query($con, $sql)) {
        header("Location: index.php"); // Đã sửa
        exit();
    } else echo "Lỗi: " . mysqli_error($con);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container { margin-top: 30px; max-width: 600px; }
        img { max-width: 120px; border-radius: 4px; margin-top: 8px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-primary mb-4">Sửa sản phẩm (ID: <?= $id ?>)</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Tên sản phẩm:</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="form-group">
            <label>Giá (VND):</label>
            <input type="number" name="price" class="form-control" min="0" step="0.01" required value="<?= htmlspecialchars($product['price']) ?>">
        </div>
        <div class="form-group">
            <label>Trạng thái:</label>
            <select name="status" class="form-control" required>
                <option value="In Stock" <?= $product['status'] == 'In Stock' ? 'selected' : '' ?>>In Stock</option>
                <option value="Out of Stock" <?= $product['status'] == 'Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
            </select>
        </div>
        <div class="form-group">
            <label>Ảnh sản phẩm:</label><br>
            <input type="file" name="image" accept=".jpg,.jpeg,.png" class="form-control-file">
            <?php if ($product['image']): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Ảnh hiện tại">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a> </form>
</div>
</body>
</html>