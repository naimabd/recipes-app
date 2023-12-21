<?php
session_start();
require "../_auth/check_login.php";
require "../_public/connection.php";
// api call
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipeID = !!isset($_POST['id']) ? $_POST['id'] : null;
    header('Content-Type: application/json');
    if (!$recipeID) {
        echo json_encode(array('status' => false, 'message' => 'Invalid recipe ID'));
        mysqli_close($con);
        exit();
    }
    $userID = $_SESSION['id'];
    $result = mysqli_query($con, "SELECT * FROM wishlist WHERE userID = '$userID' AND recipeID = '$recipeID'");

    if (mysqli_num_rows($result) == 1) {
        $result = mysqli_query($con, "DELETE FROM wishlist WHERE userID = '$userID' AND recipeID = '$recipeID'");
        if ($result) {
            echo json_encode(array('status' => true, 'message' => 'Recipe removed FROM wishlist'));
        } else {
            echo json_encode(array('status' => false, 'message' => 'Failed to remove recipe FROM wishlist'));
        }
    } else {
        $result = mysqli_query($con, "INSERT INTO wishlist (userID, recipeID) VALUES ('$userID', '$recipeID')");
        if ($result) {
            echo json_encode(array('status' => true, 'message' => 'Recipe added to wishlist'));
        } else {
            echo json_encode(array('status' => false, 'message' => 'Failed to add recipe to wishlist'));
        }
    }
}
mysqli_close($con);
?>