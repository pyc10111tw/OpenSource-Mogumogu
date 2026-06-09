<?php
require_once __DIR__ . "/../private/db.php";

$stmt = $pdo->query("SELECT health FROM pets LIMIT 1");
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

$healthValue = $pet ? $pet['health'] : 100;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoguMogu Pet Status</title>
    <link rel="stylesheet" href="petstatus.css">
</head>
<body>
    <div id="petStatusContainer">
        <div id="petStatusTitle">Pet Status!</div>
        <div id="petStatusBox">
            <div id="petHealthContainer">
                <!-- this is ducky -->
                <div id="petImageContainer">
                    <img id="petSize" src="/assets/images/duckpet.png">
                    <p id="petName">Ducky!</p>
                </div>
                <!-- this is the health status -->
                <div id="healthBarContainer">
                    <div id="healthBarContents">
                        <p>Health Bar:</p>
                        <div id="healthBarDisplay">
                            <div id="healthBarGray">
                                <div id="healthBarGreen"></div>   <!-- sit on top of the gray health bar -->
                            </div>
                            <div style="font-size: 15px; margin-left: 5px;" id="healthPercentage">100%</div>  
                        </div>
                        <div><a href="logmeal.html"><button id="logMealButton">Log Meal</button></a></div>
                    </div>
                </div>
            </div>
            <div id="petHealthGuide">
                <div class="guideContainer">
                    <div id="happyStatus">
                        <img class="reactionImage" src="/assets/images/happyStatus.png">
                        <div class="statusDescription">Well Fed</div>
                    </div>
                    <div class="statusDescription">75-100%</div>
                </div>
                <div class="guideContainer">
                    <div id="goodStatus">
                        <img class="reactionImage" src="/assets/images/goodStatus.png">
                        <div class="statusDescription">Getting Hungry</div>
                    </div>
                    <div class="statusDescription">50-74%</div>
                </div>
                <div class="guideContainer">
                    <div id="hungryStatus">
                        <img class="reactionImage" src="/assets/images/hungryStatus.png">
                        <div class="statusDescription">Very Hungry</div>
                    </div>
                    <div class="statusDescription">25-49%</div>
                </div>
                <div class="guideContainer">
                    <div id="criticalStatus">
                        <img id="critical" class="reactionImage" src="/assets/images/criticalStatus.png">
                        <div class="statusDescription">Critical</div>
                    </div>
                    <div class="statusDescription">0-24%</div>
                </div>
            </div>
        </div>
        <a href="index.php" id="backLink">← Back to Home</a>
    </div>
    <script>
        const healthValue = <?= $healthValue ?>;

        document.getElementById("healthBarGreen").style.width = healthValue + '%';  
        document.getElementById("healthPercentage").textContent = healthValue + '%';     
    </script>
</body>
</html>