<?php
session_start();
require_once 'db_connect.php';

if(!isset($_SESSION['username'])){
    header('location:login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('location: index.php?msg=deleted'); 
    } else {
        header('location: index.php?msg=error');   
    }
    mysqli_stmt_close($stmt);
} else {
    header('location: index.php?msg=error');      
}

mysqli_close($con);
?>