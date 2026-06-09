<?php
require_once __DIR__ . "/../private/db.php";
require_once "private/pet_health_logic.php";

// initialize pets (only when not exists)
$pdo->query("
    INSERT INTO pets (streak, last_fed)
    SELECT 0, NULL
    WHERE NOT EXISTS (SELECT 1 FROM pets)
");
update_health($pdo);
?>

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
        <div id="contributorsPage">
            <img id="contributorsIcon"src="/assets/images/contributors.png">
            <p id="contributors">contributors</p>
        </div>
        <div id="title"><h1>Mogu Mogu Meal Tracker!</h1></div>
        <img id="pet"src="/assets/images/duckpet.png">
        <a href="petstatus.php">
            <div id="petStatus">Pet Status</div>
        </a>
     </div>
     <div class="navigation">
        <div class="cards">
            <a href="logmeal.html">
                <div id="logMeal"><img class="navImages" src="/assets/images/foodbowl.png"></div>
                <div class="navText">Log Meal</div>
            </a>
        </div>
        <div class="cards">
            <a href="streak.php">
            <div id="streak"><img class="navImages"  src="/assets/images/bonfire.png"></div>
            <div class="navText">Streak</div> </a>
        </div>
        <div class="cards">
            <a href="history.php">
            <div id="mealHistory"><img class="navImages" src="/assets/images/photoalbum.png"></div>
            <div class="navText">Meal History</div>
            </a>
        </div>
     </div>

    <script src="script.js"></script>
</body>
</html>