<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. LOGIC TỰ ĐỘNG SỬA ĐƯỜNG DẪN (PATH FIX)
$path_prefix = '';
if (strpos($_SERVER['SCRIPT_NAME'], '/cart_module/') !== false || strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) {
    $path_prefix = '../';
}

// 2. KẾT NỐI DATABASE
// Dùng __DIR__ để luôn tìm thấy file db_connect.php dù header được gọi từ bất cứ đâu
require_once __DIR__ . '/../db_connect.php'; 

// 3. XÁC ĐỊNH TRANG HIỆN TẠI & QUYỀN ADMIN
$current_page = basename($_SERVER['PHP_SELF']); 
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// 4. LOGIC GIỎ HÀNG (Chỉ đếm số lượng khi User thường đăng nhập)
$cart_count = 0;
if (isset($_SESSION['username']) && !$isAdmin) {
    $u_temp = $_SESSION['username'];
    // Lấy user_id
    $q_u = mysqli_query($con, "SELECT id FROM users WHERE username = '$u_temp'");
    if ($r_u = mysqli_fetch_assoc($q_u)) {
        $uid = $r_u['id'];
        // Tính tổng số lượng trong cart
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
    <title>Bakery House</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Pacifico&display=swap" rel="stylesheet">
    
    <style>
        /* --- BẢNG MÀU NÂU BAKERY (Giữ nguyên style cũ) --- */
        :root {
            --primary-brown: #5D4037;
            --light-brown: #8D6E63;
            --cream-bg: #FDFBF7;
            --text-dark: #3E2723;
            --gold-accent: #FFCA28;
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
            font-family: 'Pacifico', cursive;
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

        /* Buttons & Icons */
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
        main { flex: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= $isAdmin ? $path_prefix.'products.php' : $path_prefix.'index.php' ?>">
            <i class="fas fa-cookie-bite mr-2"></i>Bakery House
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
            <span class="navbar-toggler-icon"><i class="fas fa-bars" style="color:var(--primary-brown)"></i></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ml-auto align-items-center">
                
                <?php if (!$isAdmin): ?>
                    <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $path_prefix ?>index.php">Home</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item <?= ($current_page == 'products.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= $path_prefix ?>products.php">
                        <?= $isAdmin ? 'Products' : 'Products' ?>
                    </a>
                </li>
                
                <?php if ($isAdmin): ?>
                    <li class="nav-item <?= ($current_page == 'manage_users.php') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $path_prefix ?>admin/manage_users.php">Users</a>
                    </li>
                    <li class="nav-item <?= ($current_page == 'messages.php') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $path_prefix ?>admin/messages.php">Messages</a>
                    </li>
                <?php endif; ?>

                <?php if (!$isAdmin): ?>
                    <li class="nav-item <?= ($current_page == 'contact.php') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= $path_prefix ?>contact.php">Contact</a>
                    </li>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['username'])): ?>
                    
                    <?php if (!$isAdmin): ?>
                        <li class="nav-item ml-3">
                            <a href="<?= $path_prefix ?>cart_module/view.php" class="cart-icon-wrap">
                                <i class="fas fa-shopping-cart"></i>
                                <?php if($cart_count > 0): ?>
                                    <span class="cart-badge"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn-outline-brown" href="#" id="userDrop" data-toggle="dropdown">
                            <i class="fas fa-user mr-1"></i> <?= $_SESSION['username'] ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= $path_prefix ?>logout.php">Logout</a>
                        </div>
                    </li>

                <?php else: ?>
                    <li class="nav-item ml-3">
                        <a href="<?= $path_prefix ?>login.php" class="btn btn-brown">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main>