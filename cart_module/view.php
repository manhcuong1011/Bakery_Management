<?php
// cart_module/view.php - Xem giỏ hàng (Phiên bản Read-only)
require_once '../session.php';
require_once '../db_connect.php';

requireLoggedIn();

// Lấy User ID
$username = $_SESSION['username'];
$u_res = mysqli_query($con, "SELECT id FROM users WHERE username = '$username'");
$user_id = mysqli_fetch_assoc($u_res)['id'];

// XỬ LÝ XÓA (Chỉ giữ lại chức năng xóa)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_item'])) {
    $cart_id = (int)$_POST['cart_id'];
    mysqli_query($con, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    header("Location: view.php");
    exit();
}

// LẤY DỮ LIỆU
$query = "SELECT c.id as cart_id, c.quantity, p.name, p.price, p.image 
          FROM cart c JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = $user_id";
$result = mysqli_query($con, $query);
$total_cart_value = 0;

include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family: 'Pacifico', cursive; color: var(--primary-brown);">Your Shopping Cart</h2>
        <a href="../products.php" class="btn btn-outline-brown rounded-pill">
            <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
        </a>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="bg-white p-4 rounded shadow-sm">
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--primary-brown); color: #fff;">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th class="text-center">Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <?php 
                        $subtotal = $row['price'] * $row['quantity']; 
                        $total_cart_value += $subtotal;
                        // Sửa lỗi hiển thị ảnh
                        $img_path = $row['image'] ? "../" . $row['image'] : "https://placehold.co/80x80";
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= $img_path ?>" width="70" class="rounded mr-3 shadow-sm">
                                <span class="font-weight-bold" style="color: var(--text-dark);"><?= htmlspecialchars($row['name']) ?></span>
                            </div>
                        </td>
                        <td><?= number_format($row['price']) ?> đ</td>
                        
                        <td class="text-center font-weight-bold" style="font-size: 1.1em;">
                            <?= $row['quantity'] ?>
                        </td>
                        
                        <td class="font-weight-bold" style="color: var(--primary-brown);">
                            <?= number_format($subtotal) ?> đ
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                                <button type="submit" name="delete_item" class="btn btn-sm btn-light text-danger" onclick="return confirm('Remove this item?');">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-6"></div>
                <div class="col-md-6 text-right">
                    <h4 class="mb-3">Total: <span style="color: #d32f2f; font-weight: bold;"><?= number_format($total_cart_value) ?> VND</span></h4>
                    <a href="checkout.php" class="btn btn-brown btn-lg px-5 shadow">
                        Proceed to Checkout <i class="fas fa-check ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5 bg-white rounded shadow-sm">
            <i class="fas fa-shopping-basket mb-3" style="font-size: 4rem; color: #e0e0e0;"></i>
            <h4 class="text-muted">Your cart is empty!</h4>
            <a href="../products.php" class="btn btn-brown rounded-pill px-4">Browse Menu</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
