<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mogu Mogu Meal Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HOMEPAGE -->
     <div class="homepageContainer">
        <div id="title"><h1>Mogu Mogu Meal Tracker!</h1></div>
        <img id="pet"src="/public/assets/images/duckpet.png">
        <div id="petStatus">Pet Status</div>
     </div>
     <div class="navigation">
        <div class="cards">
            <a href="logmeal.html">
                <div id="logMeal"><img class="navImages" src="/public/assets/images/foodbowl.png"></div>
                <div class="navText">Log Meal</div>
            </a>
        </div>
        <div class="cards">
            <a href="streak.html">
            <div id="streak"><img class="navImages"  src="/public/assets/images/bonfire.png"></div>
            <div class="navText">Streak</div> </a>
        </div>
        <div class="cards">
            <div id="mealHistory"><img class="navImages" src="/public/assets/images/photoalbum.png"></div>
            <div class="navText">Meal History</div>
        </div>
     </div>

    <script src="script.js"></script>
</body>
</html>