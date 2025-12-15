<?php
require_once 'session.php';
require_once 'db_connect.php'; 

// Include Header
include 'includes/header.php';

// --- XỬ LÝ TÌM KIẾM & LỌC SẢN PHẨM ---
// Chỉ lấy những sản phẩm chưa bị "Xóa mềm" (status != 'Deleted')
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $query = "SELECT * FROM products WHERE name LIKE '%$search%' AND status != 'Deleted' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM products WHERE status != 'Deleted' ORDER BY id DESC";
}

$result = mysqli_query($con, $query);
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<div class="container mt-5">
    
    <div class="text-center mb-4">
        <h2 style="font-family: 'Pacifico', cursive; color: var(--primary-brown); font-size: 2.5rem;">
            Our Delicious Menu
        </h2>
        <p class="text-muted">Handcrafted with love for your delight</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <form action="" method="GET" class="input-group shadow-sm" style="border-radius: 30px; overflow: hidden;">
                <input type="text" name="search" class="form-control border-0 py-4 pl-4" 
                       placeholder="Find your favorite cake..." 
                       value="<?= htmlspecialchars($search) ?>" 
                       style="background-color: #fff;">
                
                <div class="input-group-append">
                    <button class="btn btn-brown px-4" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
            
            <?php if($search): ?>
                <div class="text-center mt-2">
                    <a href="products.php" class="text-muted small">
                        <i class="fas fa-times-circle"></i> Clear search & Show all
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($isAdmin): ?>
        <div class="bg-white p-4 rounded shadow-sm mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-dark mb-0">Product Management</h4>
                <a href="admin/create.php" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add Product
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Img</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td>
                                    <img src="<?= $row['image'] ?: 'https://placehold.co/50x50' ?>" width="50" class="rounded">
                                </td>
                                <td class="font-weight-bold"><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= number_format($row['price']) ?> đ</td>
                                
                                <td>
                                    <?php if($row['status'] == 'In Stock'): ?>
                                        <span class="badge badge-success px-2 py-1">In Stock</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary px-2 py-1">Out of Stock</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="admin/edit.php?id=<?= $row['id'] ?>" class="text-primary mr-3" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="admin/delete.php" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No products found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php 
                mysqli_data_seek($result, 0); 
                ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 border-0 shadow-sm product-card" style="border-radius: 15px; overflow: hidden; transition: transform 0.3s;">
                            <div style="height: 200px; overflow: hidden; position: relative;">
                                <img src="<?= $row['image'] ?: 'https://placehold.co/300x300' ?>" class="w-100 h-100" style="object-fit: cover;">
                                <?php if($search): ?>
                                    <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(255,255,255,0.8); padding:5px; text-align:center; font-size:0.8rem; color:#5D4037;">Match found</div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font-weight-bold text-dark"><?= htmlspecialchars($row['name']) ?></h5>
                                
                                <div class="mb-2">
                                    <?php if($row['status'] == 'In Stock'): ?>
                                        <span class="badge badge-pill badge-success" style="font-size: 0.7em;">In Stock</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-secondary" style="font-size: 0.7em;">Out of Stock</span>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="h5 mb-0" style="color: var(--primary-brown);"><?= number_format($row['price']) ?> đ</span>
                                    </div>
                                    
                                    <?php if($row['status'] == 'In Stock'): ?>
                                        <form action="cart_module/add.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                            <button class="btn btn-brown btn-block shadow-sm">Add to Cart</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-block shadow-sm" disabled>Sold Out</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-cookie-bite fa-3x mb-3" style="color: #ddd;"></i>
                    <h4 class="text-muted">Oops! No cakes found.</h4>
                    <p class="text-muted">Try searching for something else.</p>
                    <a href="products.php" class="btn btn-outline-brown mt-2">View Full Menu</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>