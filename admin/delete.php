<?php
require_once '../session.php'; 

// 1. Check quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

require_once '../db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = (int) $_POST['id'];


    $sql = "DELETE FROM products WHERE id = $id";
    
    if (mysqli_query($con, $sql)) {
        header("Location: ../products.php"); 
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
} else {
    header("Location: ../products.php");
    exit();
}
?>