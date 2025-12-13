<?php
// admin/manage_users.php
session_start();

// 1. CHẶN KHÔNG CHO USER THƯỜNG TRUY CẬP
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Nếu không phải admin, đá về trang chủ
    header("Location: ../index.php");
    exit();
}

require_once '../db_connect.php'; 

// 2. XỬ LÝ XÓA USER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $current_user = $_SESSION['username'];
    
    // Lấy ID của người đang đăng nhập hiện tại để so sánh
    $q_curr = mysqli_query($con, "SELECT id FROM users WHERE username = '$current_user'");
    $curr_id = mysqli_fetch_assoc($q_curr)['id'];

    if ($delete_id === $curr_id) {
        echo "<script>alert('Không thể tự xóa tài khoản của chính mình!');</script>";
    } else {
        // Thực hiện xóa (nhờ ON DELETE CASCADE, nó sẽ tự xóa giỏ hàng và đơn hàng của user này)
        $sql = "DELETE FROM users WHERE id = $delete_id";
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Đã xóa thành công!'); window.location.href='manage_users.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . mysqli_error($con) . "');</script>";
        }
    }
}

// 3. LẤY DANH SÁCH USER
$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($con, $query);

// Gọi Header (Lưu ý đường dẫn ../)
include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family: 'Pacifico', cursive; color: var(--primary-brown);">User Management</h2>
        <a href="../products.php" class="btn btn-outline-brown rounded-pill">
            <i class="fas fa-arrow-left mr-2"></i> Back to Products
        </a>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <table class="table table-hover align-middle">
            <thead style="background-color: var(--primary-brown); color: #fff;">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Fullname</th>
                    <th>Contact</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td class="font-weight-bold"><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <?php if($row['role'] == 'admin'): ?>
                            <span class="badge badge-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge badge-info">User</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['fullname'] ?? 'N/A') ?></td>
                    <td class="small">
                        Phone: <?= htmlspecialchars($row['phone'] ?? '---') ?><br>
                        Addr: <?= htmlspecialchars($row['address'] ?? '---') ?>
                    </td>
                    <td class="text-center">
                        <?php 
                        // Kiểm tra lại lần nữa để không hiện nút xóa cho chính mình
                        $is_me = ($row['username'] === $_SESSION['username']);
                        ?>
                        
                        <?php if(!$is_me): ?>
                            <form method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa user này sẽ xóa toàn bộ đơn hàng và giỏ hàng của họ. Bạn có chắc chắn không?');">
                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted small">(You)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>