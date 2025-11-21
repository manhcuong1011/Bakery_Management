<?php
session_start();

// Kết nối database
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');
if (!$con) die("Kết nối thất bại: " . mysqli_connect_error());
mysqli_set_charset($con, "utf8");

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Lấy ID sản phẩm
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("ID sản phẩm không hợp lệ.");

// Lấy dữ liệu hiện tại
$product = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM products WHERE id = $id"));
if (!$product) die("Không tìm thấy sản phẩm.");

// Cập nhật sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = mysqli_real_escape_string($con, trim($_POST['name']));
    $price  = (float)$_POST['price'];
    $status = mysqli_real_escape_string($con, trim($_POST['status']));
    $image  = $product['image']; // Giữ ảnh cũ nếu không upload mới

    // Nếu có file upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetFile = $targetDir . time() . '_' . basename($_FILES["image"]["name"]);

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($fileType, $allowed) && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $targetFile;
        }
    }

    $sql = "UPDATE products SET name='$name', price=$price, status='$status', image='$image' WHERE id=$id";
    if (mysqli_query($con, $sql)) {
        header("Location: home.php");
        exit();
    } else echo "Lỗi: " . mysqli_error($con);
}
mysqli_close($con);
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
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Ảnh sản phẩm hiện tại">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="home.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
