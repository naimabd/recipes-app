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
    $result = mysqli_query($con, "SELECT * FROM likes WHERE userID = '$userID' AND recipeID = '$recipeID'");
    $queryLikes = "SELECT COUNT(*) FROM likes WHERE recipeID = " . $recipeID;

    if (mysqli_num_rows($result) == 1) {
        $result = mysqli_query($con, "DELETE FROM likes WHERE userID = '$userID' AND recipeID = '$recipeID'");
        if ($result) {
            $resultLikes = mysqli_query($con, $queryLikes);
            $likes = mysqli_fetch_array($resultLikes)[0];
            echo json_encode(array('status' => true, 'likes' => $likes, 'message' => 'Recipe unliked'));
        } else {
            echo json_encode(array('status' => false, 'message' => 'Failed to unlike recipe'));
        }
    } else {
        $result = mysqli_query($con, "INSERT INTO likes (userID, recipeID) VALUES ('$userID', '$recipeID')");
        if ($result) {
            $resultLikes = mysqli_query($con, $queryLikes);
            $likes = mysqli_fetch_array($resultLikes)[0];
            echo json_encode(array('status' => true, 'likes' => $likes, 'message' => 'Recipe liked'));
        } else {
            echo json_encode(array('status' => false, 'message' => 'Failed to like recipe'));
        }
    }
}
mysqli_close($con);
?>