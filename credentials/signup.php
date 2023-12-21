<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../_public/style.css">
</head>

<body>
    <?php
    session_start();
    require "../_public/connection.php";
    require "../_auth/redirect_role.php";

    if (!!isset($_POST['email']) && !!isset($_POST['username']) && !!isset($_POST['first_name']) && !!isset($_POST['last_name']) && !!isset($_POST['password']) && !!isset($_POST['repassword'])) {
        $email = strtolower($_POST['email']);
        $username = strtolower($_POST['username']);
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $password = md5($_POST['password']);
        $repassword = md5($_POST['repassword']);

        if ($password != $repassword) {
            echo "<script>alert('Passwords do not match'); window.location.href = '/project/credentials/signup.php'</script>";
            mysqli_close($con);
            exit();
        }

        $result = mysqli_query($con, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");

        if (mysqli_num_rows($result) == 0) {
            $result = mysqli_query($con, "INSERT INTO users (username, first_name, last_name, email, password, userType) VALUES ('$username', '$first_name', '$last_name', '$email', '$password', 'regular')");
            if ($result) {
                echo "<script>alert('User created successfully')</script>";
                $result = mysqli_query($con, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");
                $row = mysqli_fetch_array($result);
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['userID'];
                header("Location: /project/regular");
                mysqli_close($con);
                exit();
            } else {
                echo "<script>alert('Error creating user'); window.location.href = '/project/credentials/signup.php'</script>";
            }
        } else {
            echo "<script>alert('User already exists'); window.location.href = '/project/credentials/signup.php'</script>";
        }
    }
    mysqli_close($con);
    ?>
    <div class="container credentials">
        <h1>Sign Up</h1>
        <form method="post" class="add show" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="full">
                <label for="email">email</label>
                <input type="email" name="email" id="email" placeholder="type your email" required>
            </div>
            <div class="full">
                <label for="username">username</label>
                <input type="text" name="username" id="username" placeholder="type your username"
                    pattern="[a-zA-Z0-9_]{4,15}" required>
            </div>
            <div>
                <label for="first_name">first name</label>
                <input type="text" name="first_name" id="first_name" placeholder="type your first name" required>
            </div>
            <div>
                <label for="last_name">last name</label>
                <input type="text" name="last_name" id="last_name" placeholder="type your last name" required>
            </div>
            <div>
                <label for="password">password</label>
                <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*\W).{8,}" placeholder="type your password" required>
            </div>
            <div>
                <label for="repassword">confirm password</label>
                <input type="password" name="repassword" id="repassword" placeholder="retype your password" required>
            </div>
            <div class="full">
                <button type="submit" class="add"> Sign Up </button>
                <button type="button" class="add secondary"
                    onclick="window.location.href = '/project/credentials/login.php'"> Login </button>
            </div>
        </form>
    </div>
</body>

</html>