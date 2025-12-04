<?php
// index.php - TRANG CHỦ (Landing Page)
require_once 'session.php'; // Session & DB
include 'includes/header.php';
?>

<div style="
    background: linear-gradient(rgba(62, 39, 35, 0.7), rgba(62, 39, 35, 0.7)), url('https://images.unsplash.com/photo-1509365465985-25d11c17e812?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
    background-size: cover;
    background-position: center;
    height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
">
    <div class="container">
        <h1 class="display-3 font-weight-bold mb-4" style="font-family: 'Pacifico', cursive;">Welcome to Bakery House</h1>
        <p class="lead mb-5" style="font-size: 1.5rem; max-width: 700px; margin: 0 auto;">
            Experience the taste of perfection. Freshly baked breads, pastries, and cakes made with the finest ingredients.
        </p>
        <a href="products.php" class="btn btn-brown btn-lg px-5 py-3 shadow" style="font-size: 1.2rem;">
            Order Now <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

