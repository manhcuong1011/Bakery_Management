<?php
// login.php - Final Slim Version
require_once 'session.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$reg_error = "";

// LOGIN LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_action'])) {
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $pass = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$user'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            if (isset($_POST['remember'])) {
                setcookie('remember_user', $user, time() + (86400 * 30), "/");
            }
            if($row['role'] === 'admin'){
                header("Location: products.php");
            }else{
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}

// Catch Registration Error
if(isset($_GET['error']) && $_GET['error'] == 'exists') {
    $reg_error = "Username taken.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>

        :root{
            --bg-gradient-start: #3e2723;
            --bg-gradient-end: #8d6e63;
            --primary-brown: #5d4037;
            --hover-brown: #3e2723;
            --light-bg: #FDFBF7;

        }
        body {
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        .main-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 900px;
            max-width: 95%;
            overflow: hidden;
            display: flex;
        }
        .side {
            width: 50%;
            padding: 50px 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Căn giữa tất cả nội dung theo chiều ngang */
            justify-content: center;
        }
        .right-side {
            background-color: var(--light-bg);
            border-left: 1px solid #EFEBE9;
        }

        /* HEADINGS */
        h2 { font-weight: 600; font-size: 24px; color: #4E342E; margin-bottom: 5px; }
        p.sub { font-size: 13px; color: #8D6E63; margin-bottom: 20px; }

        /* ERROR BOX FIXED HEIGHT */
        .msg-box {
            height: 30px;
            width: 100%;
            max-width: 280px; /* Bằng chiều rộng input */
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
        }
        .alert-tiny {
            padding: 4px 10px; font-size: 12px; border-radius: 6px; width: 100%; text-align: center; margin: 0;
        }

        /* FORM STYLING */
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center; /* Quan trọng: Căn giữa input và button */
        }
        
        .form-control {
            width: 100%;
            max-width: 280px; /* GIỚI HẠN CHIỀU RỘNG INPUT */
            height: 42px;
            border-radius: 50px; /* Bo tròn kiểu viên thuốc */
            padding: 0 20px;
            font-size: 13px;
            border: 1px solid #D7CCC8;
            background: #fff;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: var(--primary-brown);
            box-shadow: 0 0 0 4px rgba(93, 64, 55, 0.1);
        }

        /* CHECKBOX & SPACER ALIGNMENT */
        .checkbox-container {
            width: 100%;
            max-width: 280px; /* Căn thẳng với input */
            height: 25px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-left: 5px;
        }
        .form-check-label { font-size: 12px; color: #6D4C41; cursor: pointer; margin-left: 5px; }
        
        .spacer {
            height: 25px; /* Chiều cao bằng checkbox-container */
            margin-bottom: 15px;
            width: 100%;
        }

        /* BUTTONS */
        .btn-custom {
            width: 100%;
            max-width: 280px; /* GIỚI HẠN CHIỀU RỘNG BUTTON */
            height: 42px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login {
            background: var(--primary-brown);
            color: #fff;
            box-shadow: 0 4px 15px rgba(93, 64, 55, 0.3);
        }
        .btn-login:hover { 
            background: var(--hover-brown); 
            transform: translateY(-2px); 
        }
        
        .btn-register {
            background: transparent;
            border: 2px solid var(--primary-brown); 
            color: var(--primary-brown);
        }
        .btn-register:hover { 
            background: var(--primary-brown); 
            color: #fff; }

        @media (max-width: 768px) {
            .main-card { flex-direction: column; width: 90%; margin: 20px 0; height: auto;}
            .side { width: 100%; padding: 30px 20px; }
            .right-side { border-left: none; border-top: 1px solid #eee; }
            .spacer { display: none; }
        }
    </style>
</head>
<body>

<div class="main-card">
    
    <div class="side">
        <h2>Welcome Back</h2>
        <p class="sub">Please enter your details</p>

        <div class="msg-box">
            <?php if($error): ?>
                <div class="alert alert-danger alert-tiny"><?= $error ?></div>
            <?php endif; ?>
        </div>

        <form action="" method="post">
            <input type="hidden" name="login_action" value="1">
            
            <input type="text" name="user" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>

            <div class="checkbox-container">
                <input type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-custom btn-login">Sign In</button>
        </form>
    </div>

    <div class="side right-side">
        <h2>New Here?</h2>
        <p class="sub">Create an account for free</p>

        <div class="msg-box">
            <?php if($reg_error): ?>
                <div class="alert alert-warning alert-tiny"><?= $reg_error ?></div>
            <?php endif; ?>
        </div>

        <form action="registration.php" method="post">
            <input type="text" name="user" class="form-control" placeholder="Choose Username" required>
            <input type="password" name="password" class="form-control" placeholder="Create Password" required>

            <div class="spacer"></div>

            <button type="submit" class="btn btn-custom btn-register">Sign Up</button>
        </form>
    </div>

</div>

</body>
</html>

