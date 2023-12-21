<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes / Admin</title>
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
            <h1>All Recipes</h1>
            <button class="add"> Add Recipe </button>
        </div>

        <?php
        $title = "";
        $description = "";
        $category = "";
        $ingredients = "";
        $instructions = "";
        $imageData = "";
        $formState = "";
        $idInput = "";
        $userID = $_SESSION['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !!isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM recipes WHERE recipeID = $id";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_array($result);
            if ($row) {
                $queryCategory = "SELECT * FROM recipecategory WHERE recipeID = $id";
                $resultCategory = mysqli_query($con, $queryCategory);
                $category = array();
                while ($rowCategory = mysqli_fetch_array($resultCategory)) {
                    array_push($category, $rowCategory['categoryID']);
                }
                $title = $row['title'];
                $description = $row['description'];
                $ingredients = $row['ingredients'];
                $instructions = $row['instructions'];
                $imageData = $row['imgData'];
                $idInput = '<input type="hidden" name="recipeID" value="' . $id . '">';
                $formState = "show";
            } else
                echo "No data found !!";
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!!isset($_POST["category"])) {
                $id = !!isset($_POST['recipeID']) ? $_POST['recipeID'] : false;
                $title = mysqli_real_escape_string($con, $_POST['title']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                $category = $_POST['category'];
                $ingredients = mysqli_real_escape_string($con, $_POST['ingredients']);
                $instructions = mysqli_real_escape_string($con, $_POST['instructions']);
                $imageData = $_POST['imgData'];
                if ($title && $description && $ingredients && $instructions && $imageData) {
                    if ($id) {
                        $query = "UPDATE recipes SET title = '$title', description = '$description', ingredients = '$ingredients', instructions = '$instructions', imgData = '$imageData' WHERE recipeID = $id";
                        mysqli_query($con, "DELETE FROM recipecategory WHERE recipeID = $id");
                    } else {
                        $query = "INSERT INTO recipes (title, userID, description, ingredients, instructions, imgData) VALUES ('$title', '$userID', '$description', '$ingredients', '$instructions', '$imageData')";
                    }

                    $result = mysqli_query($con, $query);
                    if (!$id)
                        $id = mysqli_insert_id($con);

                    foreach ($category as $value) {
                        mysqli_query(
                            $con, "INSERT INTO recipecategory (recipeID, categoryID, categoryName) VALUES ($id, $value, (SELECT categoryName FROM categories WHERE categoryID = $value))"
                        );
                    }
                    if ($result) {
                        echo '<script> window.location.href = "' . $_SERVER['PHP_SELF'] . '" </script>';
                    } else {
                        echo "Failed to save data !!";
                        $formState = "show";
                    }
                } else {
                    echo "Please fill all the fields !!";
                    $formState = "show";
                }
            } else {
                echo "Please select at least one category !!";
                $formState = "show";
            }
        }
        ?>

        <form class="add <?php echo $formState ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
            method="post" enctype="multipart/form-data">
            <?php echo $idInput ?>
            <div class="full row">
                <div class="image">
                    <label for="image">
                        <button type="button" class="delete" onclick="removeImage(event)">x</button type="button">
                        <span>Upload Image</span>
                        <img id="imgOutput" <?php echo !!$imageData ? 'src="' . $imageData . '"' : '' ?>
                            alt="recipe image">
                    </label>
                    <input type="file" name="image" id="image" accept="image/*" onchange="loadImage(event)">
                </div>
                <div class=" inputs">
                    <input type="hidden" name="imgData" <?php echo !!$imageData ? 'value="' . $imageData . '"' : '' ?>
                        id="imgData">
                    <div>
                        <label for=" title">Title</label>
                        <input type="text" name="title" id="title" placeholder="Enter title"
                            value="<?php echo $title ?>" required>
                    </div>
                    <div>
                        <label for="description">Description</label>
                        <input type="text" name="description" id="description" placeholder="Enter description"
                            value="<?php echo $description ?>" required>
                    </div>
                </div>
            </div>

            <div class="full">
                <label>Category</label>
                <div class="options">
                    <?php
                    $sql = "SELECT * FROM categories ORDER BY categoryName ASC";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $checked = $category && in_array($row["categoryID"], $category) ? "checked" : "";
                        echo '<div class="option">
                        <input type="checkbox" name="category[]" id="' . $row["categoryName"] . '" value=' . $row["categoryID"] . ' ' . $checked . '>
                        <label for="' . $row["categoryName"] . '">' . $row["categoryName"] . '</label>
                        </div>';
                    }
                    ?>
                </div>
            </div>
            <div class="full">
                <label for="ingredients">Ingredients</label>
                <textarea type="text" name="ingredients" id="ingredients" placeholder="Enter ingredients" value=""
                    required><?php echo $ingredients ?></textarea>
            </div>
            <div class="full">
                <label for="instructions">Instructions</label>
                <!-- required -->
                <textarea type="text" name="instructions" id="instructions" placeholder="Enter instructions"
                    value=""><?php echo $instructions ?></textarea>
            </div>
            <div class="full">
                <button type="submit" class="add">Submit</button>
            </div>
        </form>
        <?php
        $sql = "SELECT * FROM recipes ORDER BY title ASC";
        $result = mysqli_query($con, $sql);
        $rows = mysqli_num_rows($result);

        if ($rows > 0) {
            echo '<div class="table_container">
                        <table>
                        <thead>
                            <tr>
                                <th>title</th>
                                <th>description</th>
                                <th>ingredients</th>
                                <th>instructions</th>
                                <th>categories</th>
                                <th>delete</th>
                            </tr>
                        </thead>
                        <tbody>';

            while ($row = mysqli_fetch_array($result)) {
                $sql = "SELECT * FROM recipecategory WHERE recipeID = " . $row["recipeID"] . " ORDER BY categoryName ASC";
                $result2 = mysqli_query($con, $sql);
                $categories = "";
                while ($row2 = mysqli_fetch_array($result2)) {
                    $categories .= $row2["categoryName"] . "<br>";
                }
                $ingArr = array_filter(explode("\r\n", $row["ingredients"]), 'strlen');
                $insArr = array_filter(explode("\r\n", $row["instructions"]), 'strlen');
                $ingredients = array_map(function ($item) {
                    return "◦ " . $item;
                }, $ingArr);
                $instructions = array_map(function ($item) {
                    return "◦ " . $item;
                }, $insArr);
                $ingredients = implode("<br>", $ingredients);
                $instructions = implode("<br>", $instructions);
                echo '<tr id=' . $row["recipeID"] . '>
                            <td>' . $row["title"] . '</td>
                            <td>' . $row["description"] . '</td>
                            <td class="small">' . $ingredients . '</td>
                            <td class="small">' . $instructions . '</td>
                            <td>' . $categories . '</td>
                            <td><a href="/project/admin/recipes/delete.php?delete=' . $row["recipeID"] . '"> x </a></td>
                        </tr>';
            }

            echo '</tbody>
            </table>
            </div>';
        } else {
            echo "No Recipes Found";
        }

        mysqli_close($con);
        ?>
    </div>
</body>

</html>