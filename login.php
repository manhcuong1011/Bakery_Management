<?php
// login.php - Giao diện & Xử lý đăng nhập
require_once 'session.php';

// Nếu đã đăng nhập thì đá về trang chủ ngay
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// XỬ LÝ LOGIC ĐĂNG NHẬP (Gộp từ validation.php cũ sang đây)
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_action'])) {
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $pass = $_POST['password'];

    // Lấy thông tin user từ DB
    $query = "SELECT * FROM users WHERE username='$user'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // KIỂM TRA MẬT KHẨU (Dùng password_verify để so sánh với mã hash)
        if (password_verify($pass, $row['password'])) {
            // Đăng nhập thành công -> Lưu Session
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

            // XỬ LÝ "REMEMBER ME" (Cookie)
            if (isset($_POST['remember'])) {
                // Tạo cookie lưu username, sống 30 ngày (86400 giây * 30)
                // Lưu ý: Bài thực tế nên mã hóa token, bài tập dùng username cho đơn giản
                setcookie('remember_user', $user, time() + (86400 * 30), "/");
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Login & Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="row">
                <div class="col-md-6 login-left">
                    <h2>Login Here</h2>
                    <?php if($error): ?>
                        <div class="alert alert-danger p-2"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <input type="hidden" name="login_action" value="1">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="user" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember Me (30 days)</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>

                <div class="col-md-6 login-right">
                    <h2>Register Here</h2>
                    <?php 
                    if(isset($_GET['error']) && $_GET['error'] == 'exists') 
                        echo "<div class='alert alert-warning p-2'>Username đã tồn tại!</div>";
                    ?>
                    <form action="registration.php" method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="user" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>