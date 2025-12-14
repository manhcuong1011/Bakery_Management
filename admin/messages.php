<?php
if(isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'){
    header("Location: ../index.php");
    exit();
}
require_once '../db_connect.php';

// xử lý tin nhắn

if ($_SERVER["REQUEST_METHOD"] ==="POST" && isset($_POST['delete_id'])) {
    $deleted_id = (int)$_POST['delete_id'];
    $sql = "DELETE FROM messages WHERE id = $deleted_id";

  if (mysqli_query($con, $sql)) {
        echo "<script>alert('Đã xóa tin nhắn thành công!'); window.location.href='messages.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . mysqli_error($con) . "');</script>";
    }
}

// lấy tất cả tin nhắn
$query = "SELECT * FROM messages ORDER BY created_at DESC";
$result = mysqli_query($con, $query);

include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family: 'Pacifico', cursive; color: var(--primary-brown);">Customer Messages</h2>
        <a href="../products.php" class="btn btn-outline-brown rounded-pill">
            <i class="fas fa-arrow-left mr-2"></i> Back to Products
        </a>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--primary-brown); color: #fff;">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Date</th>
                        <th width="20%">Sender Info</th>
                        <th width="20%">Subject</th>
                        <th width="35%">Message</th>
                        <th width="5%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td class="small text-muted">
                            <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                            <span class="small text-muted"><?= htmlspecialchars($row['email']) ?></span>
                        </td>
                        <td class="font-weight-bold" style="color: var(--light-brown);">
                            <?= htmlspecialchars($row['subject']) ?>
                        </td>
                        <td>
                            <div style="max-height: 100px; overflow-y: auto; white-space: pre-wrap; font-size: 0.95em;">
                                <?= htmlspecialchars($row['message']) ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này không?');">
                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Delete Message">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i>
                <p>Hộp thư đang trống. Chưa có tin nhắn nào!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
