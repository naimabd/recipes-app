<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Info</title>
    <link rel="stylesheet" href="../_public/style.css">
</head>

<body>

    <?php
    require "../_public/nav.php";
    require "../_auth/check_login.php";
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !!isset($_GET['id'])) {
        $id = $_GET['id'];
        $userID = $_SESSION['id'];
        $sql = "SELECT recipes.recipeID, recipes.title, recipes.description, recipes.ingredients, recipes.instructions, recipes.imgData, recipes.creationDate, (SELECT COUNT(*) FROM wishlist WHERE recipes.recipeID = wishlist.recipeID AND userID = '$userID') AS wishedlist, (SELECT COUNT(*) FROM likes WHERE recipes.recipeID = likes.recipeID) AS likes, (SELECT COUNT(*) FROM recipeviews WHERE recipes.recipeID = recipeviews.recipeID) AS views FROM recipes WHERE recipeID = '$id'";

        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) == 1) {
            echo "<script>
             fetch('/project/_interaction/view.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=$id`
            })
             </script>";
            $row = mysqli_fetch_array($result);
            $title = $row['title'];
            $description = $row['description'];
            $ingredients = $row['ingredients'];
            $instructions = $row['instructions'];
            $imageData = $row['imgData'];
            $resultCategory = mysqli_query($con, "SELECT * FROM recipecategory WHERE recipeID = " . $id);
            $date = date_diff(date_create($row['creationDate']), date_create());
            $categories = "";
            while ($rowCategory = mysqli_fetch_array($resultCategory)) {
                $categories .= $rowCategory["categoryName"] . ", ";
            }
            $likes = $row['likes'];
            $views = $row['views'];
            $isInWishlist = $row['wishedlist'] == 1;
        } else {
            header("Location: /project/regular");
            mysqli_close($con);
            exit();
        }
    } else {
        header("Location: /project/regular");
        mysqli_close($con);
        exit();
    }
    mysqli_close($con);

    ?>
    <div class="container">
        <div class="first">
            <h1>Recipe Details</h1>
        </div>

        <div class="details scaleIn">
            <div class="head">
                <div class="plus">
                    <div class="date">
                        <?php echo ($date->format('%a') > 0 ? $date->format('%a') . 'd ago' : 'today early') ?>
                    </div>
                    <div class="likes">
                        <span>
                            <?php echo $likes ?>
                        </span>
                        <span>&#10084;</span>
                    </div>
                    <div class="likes">
                        <span>
                            <?php echo $views ?>
                        </span>
                        <span>&#128065;&#65039;</span>
                    </div>

                </div>
                <div class="image">
                    <img src="<?php echo $imageData ?>" alt="">
                </div>
                <div class="info">
                    <h2 class="name">
                        <?php echo $title ?>
                    </h2>
                    <span class="description">
                        <?php echo $description ?>
                    </span>
                    <span class="category">
                        <?php echo $categories ?>
                    </span>
                </div>
            </div>

            <div class="body">
                <div>
                    <h2>Ingredients:</h2>
                    <ul>
                        <?php
                        $ingredients = array_map(function ($item) {
                            return "<li>" . $item . "</li>";
                        }, array_filter(explode("\r\n", $ingredients), 'strlen'));
                        echo implode("", $ingredients);
                        ?>
                    </ul>
                </div>
                <div>
                    <h2>Instructions Steps:</h2>
                    <ol>
                        <?php
                        $instructions = array_map(function ($item) {
                            return "<li>" . $item . "</li>";
                        }, array_filter(explode("\r\n", $instructions), 'strlen'));
                        echo implode("", $instructions);
                        ?>
                    </ol>
                </div>
                <div class="full">
                    <button class="add <?php echo $isInWishlist ? "active" : "" ?>"
                        onclick="wishlist('<?php echo $row['recipeID'] ?>')">
                        <?php echo $isInWishlist ? "Remove from list" : "Add to list" ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <script>
        function wishlist(id) {
            fetch("/project/_interaction/wishlist.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            }).then(response => {
                if (response.ok) {
                    response.json().then(data => {
                        console.log(data);
                        if (data.status) {
                            const whitelistButton = document.querySelector("button.add");
                            whitelistButton.classList.toggle("active");
                            whitelistButton.innerText = whitelistButton.classList.contains("active") ? "Remove from list" : "Add to list";
                        } else alert(data.message);
                    });
                }
            });
        }
    </script>
</body>

</html>