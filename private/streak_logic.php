<?php
require_once "db.php";

// when meal logged
function update_streak($pdo) { // don't forget to call this in logmeal

    // get data from db
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

    // calculate streak
    if ($last_fed != $today) {
        if ($last_fed == $yesterday) {
            $streak += 1;
        } else {
            $streak = 1;
        }
        $total_days_logged += 1;

        if ($streak > $longest_streak) {
            $longest_streak = $streak;
        }

        $stmt = $pdo->prepare("UPDATE pets SET streak = ?, last_fed = ?, longest_streak = ?, total_days_logged = ?");
        $stmt->execute([$streak, $today, $longest_streak, $total_days_logged]);
    }
}

?>