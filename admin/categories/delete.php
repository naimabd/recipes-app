<?php
session_start();
require "../../_public/connection.php";
require "../../_auth/check_admin.php";
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !!isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM categories WHERE categoryID = $id";
    $result = mysqli_query($con, $query);
    if ($result) {
        header("Location: /project/admin/categories/index.php");
    } else {
        echo "Failed to delete data from categories table !!";
    }
    mysqli_close($con);
}
?>