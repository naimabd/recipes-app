<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./_public/style.css">

    <title>Recipes | Home</title>
</head>

<body>
    <div class="info">
        <div class="info_data">
            <h1>Discover Culinary Delights with Our Recipes</h1>
            <p>Where we bring the joy of cooking to your fingertips.
                Immerse yourself in
                a world of flavors and creativity with our carefully curated collection of recipes. From mouthwatering
                appetizers to delectable desserts, we have something for every palate. Join us on a culinary journey and
                elevate your home cooking experience.
            </p>
            <div class="info_button">
                <a href="/project/regular">
                    <button> Explore Now </button>
                </a>
            </div>
        </div>
    </div>


    <style>
        body {
            background: url("_assets/recipes.png") no-repeat 100%/100%;
            background-size: cover;
            background-attachment: fixed;
            scroll-snap-type: y mandatory;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            min-height: 100vh;
        }

        body::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.9) 10%, rgba(0, 0, 0, 0.8) 30%, rgba(0, 0, 0, 0.7) 50%, rgba(0, 0, 0, 0.6) 70%, transparent);
            z-index: -1;
        }

        div.info {
            margin: auto;
            height: calc(100vh - 100px);
            display: flex;
            flex-wrap: wrap;
            margin-left: max(10px, 10%);
            width: max(400px, 70%);
            align-items: center;
            z-index: 2;
        }

        div.info_data {
            justify-self: center;
            margin: 10px;
            display: flex;
            flex-direction: column;
            gap: 20px;

            >h1 {
                color: #559bf3;
                transition: 0.3s;
            }

            >p {
                word-spacing: 1px;
            }
        }

        div.info_button button {
            padding: 10px 20px;
            border-radius: 5px;
            background: transparent;
            border: 3px solid #559bf3;
            color: whitesmoke;
            font-size: 1rem;
            font-weight: 400;
            cursor: pointer;
        }

        div.info_button button:hover {
            background-color: #559bf330;
        }

        button {
            transition: all .5s;
        }

        button:hover {
            transform: scale(1.05) translateY(-2px);
        }

        @media screen and (width <=768px) {
            div.info_data>h1 {
                font-size: 1.5em;
            }
        }
    </style>


</body>

</html>