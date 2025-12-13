<?php
require_once 'db_connect.php'; 

// --- XỬ LÝ GỬI TIN NHẮN ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    // 1. Lấy dữ liệu từ form và làm sạch (tránh lỗi SQL injection)
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // 2. Validate đơn giản
    if (!empty($name) && !empty($email) && !empty($message)) {
        // 3. Insert vào bảng messages
        $sql = "INSERT INTO messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('✅ Tin nhắn của bạn đã được gửi thành công! Chúng tôi sẽ phản hồi sớm.');</script>";
        } else {
            echo "<script>alert('Lỗi: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Vui lòng điền đầy đủ Tên, Email và Nội dung!');</script>";
    }
}

// Gọi Header
include 'includes/header.php';
?>

<style>
    /* CSS Riêng cho trang Contact để giống ảnh bạn gửi */
    .contact-bg {
        background-color: #5D4037; /* Màu nâu đậm */
        color: #FDFBF7;
        padding: 40px;
        border-radius: 5px;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 10px;
    }
    .btn-contact {
        background-color: #5D4037;
        color: #fff;
        border-radius: 30px;
        padding: 10px 30px;
        border: none;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-contact:hover {
        background-color: #8D6E63;
        color: #fff;
    }
    .text-gold { color: #FFCA28; font-weight: bold; }
</style>

<div class="container mt-5 mb-5">
    <div class="row shadow-sm bg-white" style="border-radius: 10px; overflow: hidden;">
        
        <div class="col-md-5 contact-bg">
            <h3 class="mb-4 font-weight-bold">Get in touch</h3>
            <p class="mb-5">Have a question or want to order a custom cake?<br>We'd love to hear from you.</p>
            
            <div class="mb-4">
                <div class="text-gold mb-1">ADDRESS</div>
                <p>1 Phan Tay Nhac, Tu Liem, Hanoi</p>
            </div>
            
            <div class="mb-4">
                <div class="text-gold mb-1">PHONE</div>
                <p>+84 987 654 321</p>
            </div>
            
            <div class="mb-4">
                <div class="text-gold mb-1">EMAIL</div>
                <p>support@bakeryhouse.com</p>
            </div>
        </div>

        <div class="col-md-7 p-5 bg-white">
            <h3 class="mb-4 text-dark font-weight-bold">Send us a message</h3>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Your Name" 
                               value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : '' ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="text" name="subject" class="form-control" placeholder="Subject">
                </div>
                
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="5" placeholder="Message" required></textarea>
                </div>
                
                <button type="submit" name="send_message" class="btn btn-contact mt-2">Send Message</button>
            </form>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
