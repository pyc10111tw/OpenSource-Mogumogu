<?php
require_once __DIR__ . "/../private/db.php";

$stm = $pdo->prepare("SELECT streak, last_fed, longest_streak, total_days_logged FROM pets LIMIT 1");
$stm->execute();
$row = $stm->fetch(PDO::FETCH_ASSOC);

// variable
if (!$row) { // if no data
    $streak = 0;
    $last_fed = null;
    $longest_streak = 0;
    $total_days_logged = 0;
} else {
    $streak = $row['streak'];
    $last_fed = $row['last_fed'];
    $longest_streak = $row['longest_streak'];
    $total_days_logged = $row['total_days_logged'];
}
$today = date("Y-m-d");
$yesterday = date("Y-m-d", strtotime("-1 day"));
// for testing
// $today = "2026-06-08";
// $yesterday = "2026-06-07";

$streak_num = 0;
if ($last_fed == $today) {
    $streak_num = $streak;
} elseif ($last_fed == $yesterday) {
    $streak_num = $streak;
    // black and white fire image?
} else {
    $streak_num = 0;
    // black and white fire image?
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoguMogu Streak</title>
    <link rel="stylesheet" href="streak.css">
</head>
<body>
    <div id="streakContainer">
        <div id="streakTitle">Your Streak</div>

        <div id="streakBox">    <!--this is the yellow box -->
            <div id="streakDayContainer">
                <div><img id="streakFire"src="/assets/images/streakFire.png"></div>
                <div id="dayStreakNumber"><?php echo $streak_num; ?></div>
            </div>
            <div id="dayStreak">Day Streak</div>

            <div id="blackBox">
                <div class="streakBlackBox">
                    <div id="longestStreakNumber"><?php echo $longest_streak; ?></div>
                    <div id="longestStreak">Longest Streak</div>
                </div>
                <div class="streakBlackBox">
                    <div id="totalDaysNumber"><?php echo $total_days_logged; ?></div>
                    <div id="totalDaysLogged">Total Days Logged</div>
                </div>
            </div>
        </div>
        <a href="index.php" id="backLink">← Back to Home</a>
    </div> 
</body>
</html>