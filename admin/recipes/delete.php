<?php
session_start();
require "../../_public/connection.php";
require "../../_auth/check_admin.php";
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !!isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM recipes WHERE recipeID = $id";
    $result = mysqli_query($con, $query);
    if ($result) {
        header("Location: /project/admin/recipes/index.php");
    } else {
        echo "Failed to delete data FROM recipes table !!";
    }
    mysqli_close($con);
}



?>