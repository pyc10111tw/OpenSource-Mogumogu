<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributor Information</title>

    <style>
        body {
            background-color: #73a4fa;
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
    </style>
</head>
<body>


<?php
require_once __DIR__ . '/../private/db.php';

// 2. Check if $_GET['id'] is set
if (!isset($_GET['id'])) {

    // Show the INDEX: Query ALL members and list them as links
    $stmt = $pdo->query("SELECT id, name FROM members");

    // Link format: <a href="member.php?id=1">Name</a>
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<a href='members.php?id={$row['id']}'>{$row['name']}</a><br>";
    }

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