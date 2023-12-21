<nav>
    <ul>
        <li>
            <a href="/project/regular">Explore</a>
        </li>
        <?php
        session_start();
        $currentFolder = dirname($_SERVER['PHP_SELF']);
        $folderName = basename($currentFolder);
        if (!!isset($_SESSION['admin'])) {
            $navLinks = array(
                "recipes" => "index.php",
                "categories" => "index.php",
            );
            if (basename(dirname($currentFolder)) == "admin") {
                foreach ($navLinks as $key => $value) {
                    echo "<li class=" . ($folderName == $key ? 'active' : '') . "><a href=\"/project/admin/$key/$value\">$key</a></li>";
                }
            }
        }
        


        echo "</ul>";

        if (!!isset($_SESSION['username'])) {
            echo
                "<div class='user'>
          <div class='content'>
        <img src='/project/_assets/photo.jpg' alt='Guest'>
        <span>" . $_SESSION['username'] . "</span>
         </div>
        <div class='user_menu'>
         <span><a href='/project/regular/profile.php'>Profile</a></span>" .
                (!!isset($_SESSION['admin']) ? "<span><a href='/project/admin'>Admin</a></span>" : '')
                .
                "
                <span><input type='checkbox' class='toggleInput theme'/></span>
                <span class='logout'><a href='/project/credentials/logout.php'>Logout</a></span>
                </div>
                </div>";
        }
        ?>

        <script>
            const theme = (localStorage.getItem('theme') || 'dark') == 'light';
            const toggleInput = document.querySelector('.toggleInput.theme');
            toggleInput.checked = theme;
            toggleInput.addEventListener('click', function (e) {
                localStorage.setItem('theme', e.target.checked ? 'light' : 'dark');
            });
        </script>
</nav>