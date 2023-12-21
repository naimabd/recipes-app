<?php
if (isset($_SESSION['username'])) {
    if (!isset($con))
        require "../_public/connection.php";

    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $userType = !!isset($_SESSION['admin']) ? 'admin' : 'regular';
    $query = "SELECT * FROM users WHERE userID = '$id' AND username = '$username' AND userType = '$userType'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 0) {
        session_destroy();
        mysqli_close($con);
        header("Location: /project/credentials/login.php");
        exit();
    }
}


?>