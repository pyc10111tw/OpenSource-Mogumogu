<?php
require_once '../private/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$user_id = 1;
$meal_name = trim($_POST['meal_name'] ?? '');

if ($meal_name === '') {
    header('Location: logmeal.html?error=missing_name');
    exit;
}

$image_path = null;

if (!empty($_FILES['meal_photo']['name'])) {
    $upload_dir = __DIR__ . '/upload/';
    $ext = strtolower(pathinfo($_FILES['meal_photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) {
        header('Location: logmeal.html?error=bad_filetype');
        exit;
    }
    $filename = uniqid('meal_', true) . '.' . $ext;
    $dest = $upload_dir . $filename;
    if (move_uploaded_file($_FILES['meal_photo']['tmp_name'], $dest)) {
        $image_path = 'upload/' . $filename;
    }
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO meals (user_id, meal_name, image_path)
         VALUES (:user_id, :meal_name, :image_path)"
    );
    $stmt->execute([
        ':user_id'    => $user_id,
        ':meal_name'  => $meal_name,
        ':image_path' => $image_path,
    ]);
    header('Location: index.php?success=1');
    exit;
} catch (PDOException $e) {
    die('Database error: ' . htmlspecialchars($e->getMessage()));
}