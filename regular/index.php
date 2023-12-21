<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
    <link rel="stylesheet" href="../_public/style.css">
</head>

<body>
    <?php
    require "../_public/nav.php";
    require "../_auth/check_login.php";
    ?>
    <div class="container">
        <div class="first">
            <h1>Explore Recipes</h1>
            <div class="filter">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <select name="sort" onchange="this.form.submit()">
                        <?php
                        $options = [["newest", "Newest"], ["oldest", "Oldest"], ["Liked", "Most Liked"], ["Viewed", "Most Viewed"], ["MyLiked", "My Favorites"], ["MyList", "My List"], ["MyViewed", "History View"]];
                        $selected = isset($_GET['sort']) ? $_GET['sort'] : "newest";
                        foreach ($options as $option) {
                            $selectedOption = $selected == $option[0] ? "selected" : "";
                            echo "<option value='$option[0]' $selectedOption>$option[1]</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>
        <?php
        require "../_public/connection.php";
        $userID = $_SESSION['id'];

        $filterQuery = array(
            "newest" => "ORDER BY recipes.creationDate DESC",
            "oldest" => "ORDER BY recipes.creationDate ASC",
            "Liked" => "ORDER BY likes DESC",
            "Viewed" => "ORDER BY views DESC",
            "MyLiked" => "WHERE recipes.recipeID IN (SELECT recipeID FROM likes WHERE userID = $userID)",
            "MyList" => "WHERE recipes.recipeID IN (SELECT recipeID FROM wishlist WHERE userID = $userID)",
            "MyViewed" => "WHERE recipes.recipeID IN (SELECT recipeID FROM recipeviews WHERE userID = $userID)"
        )[$selected];
        $query = "SELECT recipes.recipeID, recipes.title, recipes.description, recipes.imgData, recipes.creationDate, (SELECT COUNT(*) FROM likes WHERE recipes.recipeID = likes.recipeID AND userID = $userID) AS liked, (SELECT COUNT(*) FROM likes WHERE recipes.recipeID = likes.recipeID) AS likes, (SELECT COUNT(*) FROM recipeviews WHERE recipes.recipeID = recipeviews.recipeID) AS views FROM recipes " . $filterQuery;
        $result = mysqli_query($con, $query);
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            echo '<div class="cards">';
            while ($row = mysqli_fetch_array($result)) {
                $img = !!isset($row['imgData']) ? $row['imgData'] : "/project/_assets/photo.jpg";
                $resultCategory = mysqli_query($con, "SELECT * FROM recipecategory WHERE recipeID = " . $row['recipeID']);
                $categories = "";
                while ($rowCategory = mysqli_fetch_array($resultCategory)) {
                    $categories .= $rowCategory["categoryName"] . ", ";
                }
                $date = date_diff(date_create($row['creationDate']), date_create());
                $likes = $row['likes'];
                $views = $row['views'];
                $liked = $row['liked'] > 0 ? " active" : "";

                echo
                    '<div class="card scaleIn" id="recipe_' . $row['recipeID'] . '">
           <div class="footer">
             <div class="date">' .
                    ($date->format('%a') > 0 ? $date->format('%a') . 'd ago' : 'today early')
                    . '</div>
                <div class="date">' .
                    $views
                    . '  &#128065;&#65039;</div>
            </div>
             <div class="body">
                <div class="image">
                    <img src="' . $img . '" alt="Guest">
                </div>
                <div class="content">
                    <span class="name">' . $row['title'] . '</span>
                    <span class="info">' . $row['description'] . '</span>
                </div>
                <div class="category">' .
                    $categories
                    . '</div>
            </div>
        <div class="footer">
            <div class="buttons">
                <button class="add likes' . $liked . '" onclick="likeRecipe(' . $row['recipeID'] . ')"><span>' . $likes . '</span> &#10084;</button>
                <button class="add" onclick="window.location.href = `/project/regular/explore.php?id=' . $row['recipeID'] . '`">&#8658;</button> 
            </div>
          </div>
        </div>';
            }
            echo '</div>';
        } else {
            echo "<center>No recipes found</center>";
        }
        mysqli_close($con);
        ?>

    </div>

    <script>
        function likeRecipe(id) {
            fetch("/project/_interaction/like.php", {
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
                            const recipeCard = document.getElementById(`recipe_${id}`);
                            const likeButton = recipeCard.querySelector(".buttons .add");
                            const likeCount = likeButton.querySelector("span");
                            likeCount.innerText = data.likes;
                            likeButton.classList.toggle("active");
                        } else alert(data.message);
                    });
                }
            });
        }

    </script>
</body>

</html>