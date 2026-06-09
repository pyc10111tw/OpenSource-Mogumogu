<?php
require_once "db.php";

function update_health($pdo) {

    // get pet info
    $stmt = $pdo->query("SELECT * FROM pets LIMIT 1");
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) return; // just in case

    $today = date("Y-m-d");
    $last_update = $pet['last_health_update'] ?? $today;
    $days = (strtotime($today) - strtotime($last_update)) / 86400; // seconds to days

    // if at least 1 day has passed
    if ($days > 0) { 

        $new_health = $pet['health'] - ($days * 25); // update health by 25% per day

        if ($new_health < 0) $new_health = 0;

        $stmt = $pdo->prepare("
            UPDATE pets
            SET health = ?,
                last_health_update = ?
        ");

        $stmt->execute([$new_health, $today]);
    }
}

?>