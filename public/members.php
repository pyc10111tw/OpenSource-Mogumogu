<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributor Information</title>

    <style>
        @font-face {
            font-family: "Starborn";
            src: url(/assets/fonts/Starborn.ttf);
        }
        body {
            background-image: url(/assets/images/wallpaper1.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;  /* makes body fullscreen height */
            overflow: hidden;  /* disables scrolling */
        }
        h1, p {
            font-family: "Fira Code", "Courier New", monospace;
        }
        p {
            font-size: 18px;
        }
        .box {
            width: 70%;
            margin: 30px auto;
        }
        .content {
            background-color: #cad8fa;
            margin: 0px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .content:hover {
            transform: translateY(-5px);
            background-color: #f0f0f0;
        }
        .member-list {
            display: flex;
            flex-direction: column;

            justify-content: center;
            align-items: center;

            min-height: 30vh;

            gap: 20px;
        }

        .member-list a {
            font-size: 1.2rem;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>


<?php
require_once __DIR__ . '/../private/db.php';

// 2. Check if $_GET['id'] is set
if (!isset($_GET['id'])) {

    // Show the INDEX: Query ALL members and list them as links
    $stmt = $pdo->query("SELECT id, name FROM members");

    echo "<h1 style='font-size: 3rem; text-align: center;'>Contributors</h1>";
    echo "<div class='member-list'>";
    // Link format: <a href="member.php?id=1">Name</a>
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<a href='members.php?id={$row['id']}'>{$row['name']}</a>";
    }
    echo "</div>";

} else {

    // Show the MEMBER: Query the DB for the specific ID
    $id = $_GET['id'];

    // IMPORTANT: prevent SQL injection (only necessary change)
    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch data and inject it into your shared HTML template
    if (!$row) {
        echo "User not found";
        exit;
    }

    echo "<div class='box'>";
    echo "<h1>Student Information</h1>";
    echo "<div class='content'>";
    echo "<p style='color: #999999'>Name: {$row['name']}</p>";
    echo "<p style='color: #666666'>Student ID: {$row['student_id']}</p>";
    echo "<p style='color: #333333'>Department: {$row['department']}</p>";
    echo "<p style='color: #000000'>University: {$row['university']}</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='box'>";
    echo "<h1>About Me</h1>";
    echo "<div class='content'>";
    echo "<p>{$row['about_me']}</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='box'>";
    echo "<h1>Contributions in the Project</h1>";
    echo "<div class='content'>";
    echo "<p>{$row['contributions']}</p>";
    echo "</div>";
    echo "</div>";
}
?>

</body>
</html>