<?php
session_start();

/* KẾT NỐI DATABASE */
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');
if (!$con) {
    die("Không thể kết nối database: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8");

/* KIỂM TRA ĐĂNG NHẬP */
if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit();
}

/* LẤY DANH SÁCH SẢN PHẨM */
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($con, $query);
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
        <h2>Bakery Management</h2>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Products List</h4>
        <a href="create.php" class="btn btn-success">+ Add Product</a>
    </div>

    <table class="table table-bordered table-hover text-center">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price (VND)</th>
                <th>Status</th>
                <th>Creat at</th>
                <th>Action</th>
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
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">There is nothing here!</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php mysqli_close($con); ?>
</div>
</body>
</html>
