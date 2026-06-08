<?php
require_once "db.php";

// when meal logged
function update_streak($pdo) { // don't forget to call this in logmeal

    $stm = $pdo->prepare("SELECT streak, last_fed FROM pets LIMIT 1");
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $streak = 0;
        $last_fed = null;
    } else {
        $streak = $row['streak'];
        $last_fed = $row['last_fed'];
    }

    $today = date("Y-m-d");
    $yesterday = date("Y-m-d", strtotime("-1 day"));

    // calculate streak
    if ($last_fed != $today) {
        if ($last_fed == $yesterday) {
            $streak += 1;
        } else {
            $streak = 1;
        }

        $stmt = $pdo->prepare("UPDATE pets SET streak = ?, last_fed = ?");
        $stmt->execute([$streak, $today]);
    }
}

?>