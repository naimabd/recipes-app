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
    $query = "SELECT * FROM recipeviews WHERE userID = '$userID' AND recipeID = '$recipeID'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO recipeviews (userID, recipeID) VALUES ('$userID', '$recipeID')";
        $result = mysqli_query($con, $query);
        if ($result) {
            echo json_encode(array('status' => true, 'message' => 'Recipe viewed'));
        } else {
            echo json_encode(array('status' => false, 'message' => 'Failed add view to recipe'));
        }
    }
}
mysqli_close($con);
?>