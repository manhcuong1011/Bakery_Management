<?php
session_start();

// 1. KẾT NỐI DATABASE (AIVEN)
$host = "bakery-db-bakery2025.j.aivencloud.com";
$username = "avnadmin";
$password = "AVNS_w4fPt6P2925yeh3Cb5R";
$dbname = "ql_banhngot"; // Đã sửa đúng tên DB
$port = 19064;

$con = mysqli_init();
mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL); 

if (!mysqli_real_connect($con, $host, $username, $password, $dbname, $port)) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8");

// 2. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit();
}

// 3. XỬ LÝ FORM
$error_msg = ""; // Biến lưu lỗi để hiển thị

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = mysqli_real_escape_string($con, trim($_POST['name']));
    $price  = (float) $_POST['price'];
    $status = mysqli_real_escape_string($con, trim($_POST['status']));
    $imagePath = ''; // Mặc định không có ảnh

    // Xử lý upload ảnh
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        
        // Kiểm tra thư mục uploads có tồn tại không (dự phòng)
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES['image']['name']);
        // Đổi tên file để tránh trùng (thêm thời gian vào trước)
        $targetFile = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileType, $allowTypes)) {
            // Thử upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                $error_msg = "Lỗi: Không thể lưu file ảnh. Kiểm tra quyền ghi thư mục uploads.";
            }
        } else {
            $error_msg = "Chỉ cho phép file ảnh JPG, JPEG, PNG, GIF.";
        }
    }

    // Nếu không có lỗi upload thì mới lưu vào DB
    if (empty($error_msg)) {
        $query = "INSERT INTO products (name, price, status, image, created_at) 
                  VALUES ('$name', $price, '$status', '$imagePath', NOW())";
        
        if (mysqli_query($con, $query)) {
            header("location: home.php");
            exit();
        } else {
            $error_msg = "Lỗi SQL: " . mysqli_error($con);
        }
    }
}
mysqli_close($con);
?>
<!-- <?php
session_start();
/*
/* KẾT NỐI DATABASE AIVEN */
    $host = "bakery-db-bakery2025.j.aivencloud.com";
    $username = "avnadmin";
    $password = "AVNS_w4fPt6P2925yeh3Cb5R";
    $dbname = "ql_banhngot";
    $port = 19064;

    // Aiven yêu cầu kết nối bảo mật (SSL), nên phải dùng cách này:
    $con = mysqli_init();
    mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL); 
    
    // Thực hiện kết nối
    if (!mysqli_real_connect($con, $host, $username, $password, $dbname, $port)) {
        die("Không thể kết nối database: " . mysqli_connect_error());
    }
    
    // Thiết lập font tiếng Việt
    mysqli_set_charset($con, "utf8");
/* KẾT NỐI DATABASE *
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
?> -->

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
