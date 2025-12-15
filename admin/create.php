<?php
require_once '../session.php'; 
require_once '../db_connect.php'; 

// 1. Check quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// XỬ LÝ KHI BẤM NÚT "ADD PRODUCT"
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = (float) $_POST['price'];
    
    // Status (Mặc định là In Stock nếu không chọn)
    $status = mysqli_real_escape_string($con, $_POST['status']);

    // Xử lý ảnh
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image); 
    $db_image_path = "uploads/" . basename($image); 

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, price, image, status) VALUES ('$name', '$price', '$db_image_path', '$status')";
        
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Thêm bánh mới thành công!'); window.location.href='../products.php';</script>";
        } else {
            echo "Lỗi SQL: " . mysqli_error($con);
        }
    } else {
        echo "<script>alert('Lỗi upload ảnh! Hãy kiểm tra lại thư mục uploads.');</script>";
    }
}

include '../includes/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: var(--primary-brown);">
                    <h4 class="mb-0">Add New Product</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Example: Chocolate" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Price (VND)</label>
                            <input type="number" name="price" class="form-control" placeholder="Example: 50000" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="In Stock">In Stock</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="image" class="form-control-file" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="add_product" class="btn btn-brown px-4">Add Product</button>
                            <a href="../products.php" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>