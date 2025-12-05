<?php
// cart_module/checkout.php - Trang thanh toán
require_once '../session.php';
require_once '../db_connect.php';

requireLoggedIn();

// Lấy thông tin user để Auto-fill
$username = $_SESSION['username'];
$u_res = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($u_res);
$user_id = $user['id'];

// Kiểm tra giỏ hàng trống
$c_check = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM cart WHERE user_id = $user_id"));
if ($c_check['total'] == 0) {
    echo "<script>alert('Your cart is empty!'); window.location.href='view.php';</script>";
    exit();
}

// Tính tổng tiền
$t_res = mysqli_query($con, "SELECT SUM(c.quantity * p.price) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
$total_money = mysqli_fetch_assoc($t_res)['total'];
include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="bg-white p-5 rounded shadow-sm">
                <h3 class="text-center mb-4" style="font-family: 'Pacifico', cursive; color: var(--primary-brown);">Checkout</h3>
                
                <div class="alert text-center mb-4" style="background-color: #EFEBE9; color: var(--primary-brown); border: 1px solid #D7CCC8;">
                    Total Amount: <strong style="font-size: 1.2rem;"><?= number_format($total_money) ?> VND</strong>
                </div>

                <form action="process_order.php" method="POST">
                    <input type="hidden" name="total_money" value="<?= $total_money ?>">
                    
                    <h5 class="mb-3" style="color: var(--text-dark);">Delivery Information</h5>
                    
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label>Full Name</label>
                            <input type="text" name="fullname" class="form-control" required value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Note (Optional)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ex: Call before delivery..."></textarea>
                    </div>

                    <hr class="my-4">
                    
                    <button type="submit" class="btn btn-brown btn-block btn-lg rounded-pill shadow">
                        CONFIRM ORDER <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                    <a href="view.php" class="btn btn-link btn-block text-muted">Back to Cart</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
