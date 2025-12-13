<?php
require_once '../session.php'; 

// 1. Check quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../db_connect.php'; 

$msg = "";

if (isset($_POST['upload'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = (float) $_POST['price'];
    $desc = mysqli_real_escape_string($con, $_POST['description'] ?? '');
    
    // Xử lý ảnh
    $image = $_FILES['image']['name'];
    // Target để upload file vật lý 
    $target = "../uploads/" . basename($image);
    // Path để lưu vào database 
    $db_image_path = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, price, image, description) VALUES ('$name', '$price', '$db_image_path', '$desc')";
        mysqli_query($con, $sql);
        header("Location: ../products.php"); 
        exit();
    } else {
        $msg = "Failed to upload image.";
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
                    <?php if($msg): ?>
                        <div class="alert alert-danger"><?= $msg ?></div>
                    <?php endif; ?>

                    <form method="POST" action="create.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price (VND)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control-file" required>
                        </div>
                        <div class="mt-4">
                            <button type="submit" name="upload" class="btn btn-brown">Save Product</button>
                            <a href="../products.php" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>