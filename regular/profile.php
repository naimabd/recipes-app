<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../_public/style.css">
</head>

<body>
    <?php
    require "../_public/connection.php";
    require "../_public/nav.php";
    require "../_auth/check_login.php";
    ?>
    <div class="container">
        <div class="first">
            <h1>User Details</h1>
        </div>
        <?php
        $userID = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE userID = " . $userID;
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result);

        $username = $row['username'];
        $email = $row['email'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $userType = $row['userType'];
        $oldpassword = "";
        $newpassword = "";
        $repassword = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = strtolower($_POST['username']);
            $email = strtolower($_POST['email']);
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $oldpassword = $_POST['oldpassword'];
            $newpassword = $_POST['newpassword'];
            $repassword = $_POST['repassword'];
            $encpass = md5($oldpassword);
            $emailChecking = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE email = '$email' AND userID != " . $userID));
            $usernameChecking = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE username = '$username' AND userID != " . $userID));
            $oldpasswordChecking = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE password = '$encpass' AND userID = " . $userID));

            if ($emailChecking > 0) {
                echo "<script>alert('Email already exists')</script>";
            } else if ($usernameChecking > 0) {
                echo "<script>alert('Username already exists')</script>";
            } else if ($oldpassword && $oldpasswordChecking == 0) {
                echo "<script>alert('Old password does not match')</script>";
            } else if ($newpassword != $repassword) {
                echo "<script>alert('Password does not match')</script>";
            } else {
                $query = "UPDATE users SET username = '$username', email = '$email', first_name = '$first_name', last_name = '$last_name' WHERE userID = " . $userID;
                $result = mysqli_query($con, $query);
                if ($result) {
                    if (!!$newpassword) {
                        $newpassword = md5($newpassword);
                        $query = "UPDATE users SET password = '$newpassword' WHERE userID = " . $userID;
                        $result = mysqli_query($con, $query);
                    }
                    $oldpassword = '';
                    $newpassword = '';
                    $repassword = '';
                    $_SESSION['username'] = $username;
                    echo "<script>alert('User details updated successfully'); window.location.href = '/project/regular/profile.php'</script>";
                } else {
                    echo "<script>alert('User details update failed')</script>";
                }
            }
        }
        mysqli_close($con);
        ?>

        <form method="post" class="add show" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="full">
                <label for="email">Email</label>
                <input type="email" value="<?php echo $email ?>" name="email" id="email" placeholder="type email"
                    required>
            </div>

            <div class="full">
                <label for="username">Username</label>
                <input type="text" value="<?php echo $username ?>" name="username" id="username"
                    placeholder="type username" pattern="[a-zA-Z0-9_]{4,15}" required>
            </div>

            <div>
                <label for="first_name">First Name</label>
                <input type="text" value="<?php echo $first_name ?>" name="first_name" id="first_name"
                    placeholder="type first name" required>
            </div>

            <div>
                <label for="last_name">Last Name</label>
                <input type="text" value="<?php echo $last_name ?>" name="last_name" id="last_name"
                    placeholder="type last name" required>
            </div>

            <div>
                <label>Account Type ?</label>
                <input type="text" value="<?php echo $userType ?> user" readonly required>
            </div>
            <div class="full"></div>
            <div class="full">Change password</div>
            <div class="full">
                <label for="oldpassword">Old Password</label>
                <input type="text" value="<?php echo $oldpassword ?>" name="oldpassword" id="oldpassword"
                    placeholder="type old password">
            </div>
            <div>
                <label for="newpassword">New Password</label>
                <input type="password" value="<?php echo $newpassword ?>" name="newpassword" id="newpassword"
                    placeholder="type new password">
            </div>
            <div>
                <label for="repassword">Confirm New Password</label>
                <input type="password" value="<?php echo $repassword ?>" name="repassword" id="repassword"
                    placeholder="re-type new password">
            </div>
            <div class="full">
                <button type="submit" class="add"> Update </button>
            </div>
        </form>


    </div>
</body>

</html>