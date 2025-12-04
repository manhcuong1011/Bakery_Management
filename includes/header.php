<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Logic để bôi đậm menu trang hiện tại
$current_page = basename($_SERVER['PHP_SELF']); 

// Logic giỏ hàng (Chỉ chạy cho role user)
require_once __DIR__ . '/../db_connect.php'; // Đảm bảo đã kết nối DB
$cart_count = 0;
if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    $u_temp = $_SESSION['username'];
    $q_u = mysqli_query($con, "SELECT id FROM users WHERE username = '$u_temp'");
    if ($r_u = mysqli_fetch_assoc($q_u)) {
        $uid = $r_u['id'];
        $q_c = mysqli_query($con, "SELECT SUM(quantity) as total FROM cart WHERE user_id = $uid");
        $d_c = mysqli_fetch_assoc($q_c);
        $cart_count = $d_c['total'] ?? 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <style>
        /* --- BẢNG BAKERY --- */
        :root {
            --primary-brown: #5D4037;  /* Nâu đậm (Espresso) */
            --light-brown: #8D6E63;    /* Nâu nhạt (Latte) */
            --cream-bg: #FDFBF7;       /* Màu kem nền */
            --text-dark: #3E2723;      /* Chữ nâu đen */
            --gold-accent: #FFCA28;    /* Màu vàng điểm xuyết */
        }

        body {
            background-color: var(--cream-bg);
            font-family: 'Nunito', sans-serif;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Pacifico', cursive; /* Font chữ viết tay cho Logo */
            font-size: 1.8rem;
            color: var(--primary-brown) !important;
        }
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 600;
            margin: 0 10px;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: var(--light-brown) !important;
        }
        .nav-item.active .nav-link {
            color: var(--primary-brown) !important;
            border-bottom: 2px solid var(--primary-brown);
        }

        /* Buttons */
        .btn-brown {
            background-color: var(--primary-brown);
            color: #fff;
            border-radius: 30px;
            padding: 8px 25px;
            border: none;
            transition: all 0.3s;
        }
        .btn-brown:hover {
            background-color: var(--light-brown);
            color: #fff;
            transform: translateY(-2px);
        }
        .btn-outline-brown {
            border: 2px solid var(--primary-brown);
            color: var(--primary-brown);
            border-radius: 30px;
            padding: 6px 20px;
            font-weight: bold;
        }
        .btn-outline-brown:hover {
            background-color: var(--primary-brown);
            color: #fff;
        }

        /* Cart Icon */
        .cart-icon-wrap {
            color: var(--primary-brown);
            font-size: 1.3rem;
            position: relative;
            margin-right: 20px;
        }
        .cart-badge {
            background-color: #d32f2f;
            color: white;
            font-size: 0.7rem;
            position: absolute;
            top: -5px;
            right: -8px;
            padding: 2px 6px;
            border-radius: 50%;
        }

        /* Footer fixation */
        main { flex: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-cookie-bite mr-2"></i>Bakery House
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
            <span class="navbar-toggler-icon"><i class="fas fa-bars" style="color:var(--primary-brown)"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item <?= ($current_page == 'products.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
                <li class="nav-item <?= ($current_page == 'contact.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item ml-3">
                        <a href="cart_module/view.php" class="cart-icon-wrap">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if($cart_count > 0): ?>
                                <span class="cart-badge"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn-outline-brown" href="#" id="userDrop" data-toggle="dropdown">
                            <i class="fas fa-user mr-1"></i> <?= $_SESSION['username'] ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item ml-3">
                        <a href="login.php" class="btn btn-brown">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main>

