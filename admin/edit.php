<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../session.php'; 
require_once '../db_connect.php'; 

// 1. Check quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Lấy ID sản phẩm
if (!isset($_GET['id'])) {
    header("Location: ../products.php");
    exit();
}
$id = (int)$_GET['id'];
$result = mysqli_query($con, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Sản phẩm không tồn tại!";
    exit();
}

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = (float) $_POST['price'];
    
    $status = mysqli_real_escape_string($con, $_POST['status']);

    // Logic update ảnh
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../uploads/" . basename($image); 
        $db_image_path = "uploads/" . basename($image); 
        
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $sql = "UPDATE products SET name='$name', price='$price', status='$status', image='$db_image_path' WHERE id=$id";
    } else {
        // Không đổi ảnh
        $sql = "UPDATE products SET name='$name', price='$price', status='$status' WHERE id=$id";
    }

    if (mysqli_query($con, $sql)) {
        header("Location: ../products.php"); 
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}

include '../includes/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: var(--primary-brown);">
                    <h4 class="mb-0">Edit Product</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Price (VND)</label>
                            <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="In Stock" <?= ($product['status'] ?? '') == 'In Stock' ? 'selected' : '' ?>>In Stock</option>
                                <option value="Out of Stock" <?= ($product['status'] ?? '') == 'Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Current Image</label><br>
                            <img src="../<?= $product['image'] ?>" width="100" class="mb-2 rounded border">
                        </div>

                        <div class="form-group">
                            <label>Change Image (Optional)</label>
                            <input type="file" name="image" class="form-control-file">
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="update" class="btn btn-brown">Update Product</button>
                            <a href="../products.php" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>