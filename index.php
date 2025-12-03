<?php
require_once 'session.php'; 

requireLoggedIn();

// Lấy danh sách sản phẩm
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($con, $query);

// Kiểm tra quyền Admin
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bánh ngọt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .container { background: #fff; padding: 30px; margin-top: 30px; border-radius: 8px; }
        img { max-width: 80px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Bakery Management <small class="text-muted" style="font-size: 0.5em">(Xin chào: <?= htmlspecialchars($_SESSION['username']) ?> - Role: <?= htmlspecialchars($_SESSION['role']) ?>)</small></h2>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Products List</h4>
        <?php if ($isAdmin): ?>
            <a href="create.php" class="btn btn-success">+ Add Product</a>
        <?php endif; ?>
    </div>

    <table class="table table-bordered table-hover text-center">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price (VND)</th>
                <th>Status</th>
                <th>Created at</th>
                <?php if ($isAdmin): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><img src="<?= $row['image'] ?: 'https://placehold.co/80x80?text=No+Img' ?>" alt="Image"></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= number_format($row['price'], 0, ',', '.') ?></td>
                    <td>
                        <span class="badge badge-<?= $row['status'] == 'In Stock' ? 'success' : 'secondary' ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    
                    <?php if ($isAdmin): ?>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?');">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="<?= $isAdmin ? 7 : 6 ?>">There is nothing here!</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php mysqli_close($con); ?>