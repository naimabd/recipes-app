<?php
require "check_user.php";
if (!isset($_SESSION['username'])) {
    header("Location: /project/credentials/login.php");
    exit();
}

?>