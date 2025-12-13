<?php
if (!isset($path_prefix)) {
    $path_prefix = '';
    if (strpos($_SERVER['SCRIPT_NAME'], '/cart_module/') !== false || strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) {
        $path_prefix = '../';
    }
}
?>
<footer style="background-color: #3E2723; color: #FDFBF7; padding: 40px 0; margin-top: auto;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h4 style="font-family: 'Pacifico', cursive; color: #D7CCC8;">Bakery House</h4>
                <p class="small mt-3">
                    The best cakes in town. Freshly baked every day with love and passion.
                </p>
            </div>

            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; color: #D7CCC8;">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= $path_prefix ?>index.php" style="color: #FDFBF7; text-decoration: none;">Home</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path_prefix ?>products.php" style="color: #FDFBF7; text-decoration: none;">Our Menu</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path_prefix ?>contact.php" style="color: #FDFBF7; text-decoration: none;">Contact Us</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; color: #D7CCC8;">Contact Info</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> 1 Phan Tay Nhac, Tu Liem, Hanoi</li>
                    <li class="mb-2"><i class="fas fa-phone mr-2"></i> +84 8888 888 888</li>
                    <li class="mb-2"><i class="fas fa-envelope mr-2"></i> hello@bakeryhouse.com</li>
                </ul>
            </div>
        </div>
        
        <hr style="background-color: #5D4037;">
        
        <div class="text-center small">
            &copy; 2025 Bakery House. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>