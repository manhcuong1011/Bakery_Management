<?php
// products.php 
require_once 'session.php';
require_once 'db_connect.php'; 

// Include Header
include 'includes/header.php';

// Logic lấy sản phẩm
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($con, $query);
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<div class="container mt-5">
    
    <?php if ($isAdmin): ?>
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Products</h2>
                <a href="admin/create.php" class="btn btn-success rounded-pill px-4">+ Add Product</a>
            </div>
            <table class="table table-hover align-middle">
                <thead class="thead-light">
                    <tr><th>ID</th><th>Img</th><th>Name</th><th>Price</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><img src="<?= $row['image'] ?: 'https://placehold.co/50x50' ?>" width="50" class="rounded"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= number_format($row['price']) ?> đ</td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <a href="admin/edit.php?id=<?= $row['id'] ?>" class="text-primary mr-2"><i class="fas fa-edit"></i></a>
                            <a href="admin/delete.php?id=<?= $row['id'] ?>" class="text-danger" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <div class="text-center mb-5">
            <h2 class="font-weight-bold" style="color: var(--primary-brown);">Our Delicious Menu</h2>
            <p class="text-muted">Handcrafted with love for your delight</p>
        </div>
        
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div style="height: 200px; overflow: hidden;">
                            <img src="<?= $row['image'] ?: 'https://placehold.co/300x300' ?>" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold text-dark"><?= $row['name'] ?></h5>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 mb-0" style="color: var(--primary-brown);"><?= number_format($row['price']) ?> đ</span>
                                </div>
                                <?php if($row['status'] == 'In Stock'): ?>
                                    <form action="cart_module/add.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-brown btn-block">Add to Cart</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-block" disabled>Out of Stock</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>