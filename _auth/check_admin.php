<?php
require "check_user.php";
if (!isset($_SESSION['admin'])) {
    echo "<script> window.location.href = '/project/regular';</script>";
    exit();
}
?>