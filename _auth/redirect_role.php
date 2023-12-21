<?php
require "check_user.php";

if (!!isset($_SESSION['username'])) {
    header("Location: /project/regular");
    exit();
} else if (!in_array(basename($_SERVER['PHP_SELF']), array("login.php", "signup.php"))) {
    header("Location: /project/credentials/login.php");
    exit();
}
?>