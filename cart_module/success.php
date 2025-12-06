<?php
// cart_module/success.php
require_once '../session.php';
$order_id = $_GET['order_id'] ?? 0;
include '../includes/header.php';
?>

<div class="container mt-5 text-center">
    <div class="bg-white p-5 rounded shadow-sm d-inline-block" style="max-width: 600px;">
        <div class="mb-4">
            <i class="fas fa-check-circle" style="font-size: 5rem; color: #4CAF50;"></i>
        </div>
        <h2 class="font-weight-bold" style="color: var(--primary-brown);">Order Placed Successfully!</h2>
        <p class="text-muted">Thank you for ordering with Bakery House.</p>
        
        <div class="alert alert-light border mt-4">
            Order ID: <strong>#<?= htmlspecialchars($order_id) ?></strong>
        </div>

        <p class="small text-muted mb-4">We will contact you shortly to confirm your delivery.</p>
        
        <a href="../products.php" class="btn btn-brown rounded-pill px-5">Continue Shopping</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
