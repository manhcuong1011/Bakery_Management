<?php
session_start();

// 1. CHẶN USER THƯỜNG
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../db_connect.php'; 

// Lấy ID Admin hiện tại
$current_username = $_SESSION['username'];
$q_curr = mysqli_query($con, "SELECT id FROM users WHERE username = '$current_username'");
$curr_id = mysqli_fetch_assoc($q_curr)['id'];

// --- XỬ LÝ 1: CẬP NHẬT QUYỀN (AUTO SAVE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = (int)$_POST['user_id'];
    $new_role = $_POST['role_value']; 

    if ($user_id === $curr_id) {
        echo "<script>alert('Không thể tự đổi quyền của chính mình!'); window.location.href='manage_users.php';</script>";
    } else {
        mysqli_query($con, "UPDATE users SET role = '$new_role' WHERE id = $user_id");
        echo "<script>window.location.href='manage_users.php';</script>";
    }
}

// --- XỬ LÝ 2: XÓA USER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    if ($delete_id === $curr_id) {
        echo "<script>alert('Không thể tự xóa mình!');</script>";
    } else {
        mysqli_query($con, "DELETE FROM users WHERE id = $delete_id");
        echo "<script>window.location.href='manage_users.php';</script>";
    }
}

$result = mysqli_query($con, "SELECT * FROM users ORDER BY id DESC");

include '../includes/header.php';
?>

<style>
    /* CSS Ép màu và Kích thước cho Dropdown */
    .role-select {
        border-width: 2px !important;
        font-weight: 700 !important;
        background-color: #fff;
        box-shadow: none !important;
        
        /* --- CHỈNH SỬA KÍCH THƯỚC Ở ĐÂY --- */
        width: 85px; /* Độ rộng hẹp lại bằng nút badge */
        padding: 4px 5px; /* Padding nhỏ lại */
        text-align-last: center; /* Căn giữa chữ trong dropdown */
        text-align: center;
        /* ----------------------------------- */
    }

    /* Style cho Admin: Đỏ đậm */
    .is-admin {
        color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    /* Style cho User: Xanh Teal đậm */
    .is-user {
        color: #17a2b8 !important;
        border-color: #17a2b8 !important;
    }
    
    /* Style cho Badge tĩnh (Của chính mình) */
    .my-badge {
        display: inline-block;
        width: 85px; /* Bằng kích thước dropdown */
        text-align: center;
    }
</style>

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
                <?php $is_me = ($row['id'] === $curr_id); ?>
                <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td class="font-weight-bold"><?= htmlspecialchars($row['username']) ?></td>
                    
                    <td>
                        <?php if(!$is_me): ?>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="update_role" value="1">
                                
                                <?php 
                                    $color_class = ($row['role'] == 'admin') ? 'is-admin' : 'is-user';
                                ?>

                                <select name="role_value" 
                                        class="form-control form-control-sm role-select <?= $color_class ?>" 
                                        onchange="this.form.submit()">
                                    <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                        <?php else: ?>
                            <span class="badge badge-danger py-2 my-badge">Admin</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['fullname'] ?? 'N/A') ?></td>
                    <td class="small">
                        <?= htmlspecialchars($row['phone'] ?? '---') ?><br>
                        <?= htmlspecialchars($row['address'] ?? '') ?>
                    </td>
                    
                    <td class="text-center">
                        <?php if(!$is_me): ?>
                            <form method="POST" onsubmit="return confirm('Chắc chắn xóa user này?');">
                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                    <i class="fas fa-trash-alt fa-lg"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted small">---</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>