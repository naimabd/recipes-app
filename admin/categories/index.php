<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories / Admin</title>
    <script defer src="../../_public/script.js"></script>
    <link rel="stylesheet" href="../../_public/style.css">
</head>

<body>

    <?php
    require "../../_public/nav.php";
    require "../../_public/connection.php";
    require "../../_auth/check_admin.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>

    <div class="container">
        <div class="first">
            <h1>All Categories</h1>
            <button class="add"> Add Category </button>
        </div>

        <?php
        $name = "";
        $formState = "";
        $idInput = "";

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !!isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM categories WHERE categoryID = $id";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_array($result);

            if ($row) {
                $name = $row['categoryName'];
                $idInput = '<input type="hidden" name="categoryID" value="' . $id . '">';
                $formState = "show";
            } else
                echo "No data found !!";

        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = !!isset($_POST['categoryID']) ? $_POST['categoryID'] : false;
            $name = mysqli_real_escape_string($con, $_POST['name']);

            if ($id) {
                $query = "UPDATE categories SET categoryName = '$name' WHERE categoryID = $id";
                mysqli_query($con, "UPDATE recipecategory SET categoryName = '$name' WHERE categoryID = $id");
            } else {
                $query = "INSERT INTO categories (categoryName) VALUES ('$name')";
            }

            $result = mysqli_query($con, $query);

            if ($result) {
                echo '<script> window.location.href = "' . $_SERVER['PHP_SELF'] . '" </script>';
            } else {
                echo "Failed to save data !!";
            }
        }
        ?>

        <form class="add <?php echo $formState ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
            method="post">
            <?php echo $idInput ?>
            <div>
                <label for="title">Category Name</label>
                <input type="text" name="name" id="name" placeholder="Enter the name" value="<?php echo $name ?>"
                    required>
            </div>

            <div class="full">
                <button type="submit" class="add">Submit</button>
            </div>
        </form>

        <?php
        $sql = "SELECT * FROM categories ORDER BY categoryName ASC";
        $result = mysqli_query($con, $sql);
        $rows = mysqli_num_rows($result);

        if ($rows > 0) {
            echo '<div class="table_container">
                        <table>
                        <thead>
                            <tr>
                                <th>name</th>
                                <th>recipes count</th>
                                <th>delete</th>
                            </tr>
                        </thead>
                        <tbody>';

            while ($row = mysqli_fetch_array($result)) {
                // query to count recipes for specific category
                $countQuery = "SELECT COUNT(*) AS count FROM recipecategory WHERE categoryID = " . $row["categoryID"];
                $countResult = mysqli_query($con, $countQuery);
                $countRow = mysqli_fetch_array($countResult);

                echo '<tr id=' . $row["categoryID"] . '>
                            <td>' . $row["categoryName"] . '</td>
                            <td class="center">' . $countRow[0] . ' </td>
                            <td><a href="/project/admin/categories/delete.php?delete=' . $row["categoryID"] . '"> x </a></td>
                        </tr>';
            }

            echo '</tbody>
            </table>
            </div>';
        } else {
            echo "No Categories Found";
        }

        mysqli_close($con);
        ?>
</body>

</html>