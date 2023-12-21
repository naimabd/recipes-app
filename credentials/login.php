<?php
session_start();
require "../_public/connection.php";
require "../_auth/redirect_role.php";
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = strtolower($_POST['username']);
    $password = md5($_POST['password']);
    $result = mysqli_query($con, "SELECT * FROM users WHERE password = '$password' AND (username = '$username' OR email = '$username')");
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['id'] = $row['userID'];
        if ($row['userType'] == 'admin') {
            $_SESSION['admin'] = true;
        }
        header("Location: /project/regular");
        mysqli_close($con);
        exit();
    } else {
        echo "<script>alert('Username or password is incorrect'); window.location.href = '/project/regular'</script>";
        mysqli_close($con);
        exit();
    }
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../_public/style.css">
</head>

<body>

    <div class="container credentials">
        <h1>Login</h1>
        <form method="post" class="add show" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="full">
                <label for="username">username</label>
                <input type="text" name="username" id="username" placeholder="type your username" required>
            </div>
            <div class="full">
                <label for="password">password</label>
                <input type="password" name="password" id="password" placeholder="type your password" required>
            </div>
            <div class="full">
                <button type="submit" class="add"> Login </button>
                <button type="button" class="add secondary"
                    onclick="window.location.href = '/project/credentials/signup.php'"> Sign Up </button>
            </div>
        </form>
    </div>
</body>

</html>