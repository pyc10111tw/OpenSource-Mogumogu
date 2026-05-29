<?php
require_once __DIR__ . '/../private/db.php';

//$user_id = 1;

try {
    $stmt = $pdo->prepare(
        "SELECT id, meal_name, image_path, logged_at
         FROM meals
         ORDER BY logged_at DESC"
    );
    $stmt->execute();
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $meals = [];
}

$meals_by_date = [];
foreach ($meals as $m) {
    $date_key = substr($m['logged_at'], 0, 10);
    $meals_by_date[$date_key][] = $m;
}

$year  = (int)($_GET['year']  ?? date('Y'));
$month = (int)($_GET['month'] ?? date('m'));

$days_in_month     = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$first_day_of_week = (int)date('w', mktime(0, 0, 0, $month, 1, $year));

$prev_month = $month - 1; $prev_year = $year;
if ($prev_month < 1)  { $prev_month = 12; $prev_year--; }
$next_month = $month + 1; $next_year = $year;
if ($next_month > 12) { $next_month = 1;  $next_year++; }



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meal_id'])) {
    $meal_id = (int)$_POST['meal_id'];
    $stmt = $pdo->prepare("DELETE FROM meals WHERE id = :id");
    $stmt->execute([':id' => $meal_id]);
    header('Location: history.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_meal_id'])) {
    $meal_id   = (int)$_POST['edit_meal_id'];
    $meal_name = trim($_POST['meal_name'] ?? '');

    $image_path = $_POST['current_image'] ?? null; // keep old photo by default

    if (!empty($_FILES['meal_photo']['name'])) {
        $upload_dir = __DIR__ . '/upload/';
        $ext        = strtolower(pathinfo($_FILES['meal_photo']['name'], PATHINFO_EXTENSION));
        $allowed    = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $filename   = uniqid('meal_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES['meal_photo']['tmp_name'], $upload_dir . $filename)) {
                $image_path = 'upload/' . $filename;
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE meals SET meal_name = :name, image_path = :img WHERE id = :id");
    $stmt->execute([':name' => $meal_name, ':img' => $image_path, ':id' => $meal_id]);
    header('Location: history.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MoguMogu Meal History</title>
  <link rel="stylesheet" href="history.css">
</head>
<body>
<div id="historyTitle">Meal History</div>

<div class="tabs">
  <button class="active" onclick="showTab('calendar-tab', this)">Calendar</button>
  <button onclick="showTab('gallery-tab', this)">Gallery</button>
</div>

<div class="contentBox">

  <div id="calendar-tab" class="tab-section active">
    <div class="cal-nav">
      <a href="?year=<?= $prev_year ?>&month=<?= $prev_month ?>">◀</a>
      <span><?= date('F Y', mktime(0,0,0,$month,1,$year)) ?></span>
      <a href="?year=<?= $next_year ?>&month=<?= $next_month ?>">▶</a>
    </div>
    <div class="calendar">
      <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d): ?>
        <div class="cal-header"><?= $d ?></div>
      <?php endforeach; ?>
      <?php for ($i = 0; $i < $first_day_of_week; $i++): ?>
        <div class="cal-day empty"></div>
      <?php endfor; ?>
      <?php
      $today = date('Y-m-d');
      for ($day = 1; $day <= $days_in_month; $day++):
        $key       = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $day_meals = $meals_by_date[$key] ?? [];
        $cls = 'cal-day';
        if ($key === $today)    $cls .= ' today';
        if (count($day_meals))  $cls .= ' has-meal';
      ?>
        <div class="<?= $cls ?>">
          <div class="day-num"><?= $day ?></div>
          <?php foreach ($day_meals as $m): ?>
            <span class="meal-label"><?= htmlspecialchars($m['meal_name']) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>

  <div id="gallery-tab" class="tab-section">
    <?php if (empty($meals)): ?>
      <div class="empty-msg">No meals logged yet. Go eat something!</div>
    <?php else: ?>
      <div class="gallery">
        <?php foreach ($meals as $m):
          $has_photo = $m['image_path'] && file_exists(__DIR__ . '/' . $m['image_path']);
          $display_date = date('M j, Y', strtotime($m['logged_at']));
        ?>
          <div class="gallery-card">
            <?php if ($has_photo): ?>
              <img src="<?= htmlspecialchars($m['image_path']) ?>" alt="<?= htmlspecialchars($m['meal_name']) ?>">
            <?php endif; ?>
            <div class="card-body">
              <div class="card-name"><?= htmlspecialchars($m['meal_name']) ?></div>
              <div class="card-date"><?= $display_date ?></div>
               <form method="POST">
                <input type="hidden" name="meal_id" value="<?= $m['id'] ?>">
                <button type="submit" class="delete-btn">Delete</button>
            </form>
             <!-- Edit toggle -->
            <button onclick="toggleEdit(<?= $m['id'] ?>)" class="Edit-btn">Edit</button>

            <!-- Edit form (hidden by default) -->
            <div id="edit-<?= $m['id'] ?>" style="display:none; margin-top:8px;">
                <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_meal_id" value="<?= $m['id'] ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($m['image_path'] ?? '') ?>">
                <input type="text" name="meal_name" value="<?= htmlspecialchars($m['meal_name']) ?>" required>
                <input type="file" name="meal_photo" accept="image/*";>
                <button type="submit">Save</button>
                </form>
            </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<a href="index.php" class="back-link">← Back to Home</a>

<script>
function showTab(id, btn) {
  document.querySelectorAll('.tab-section').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.tabs button').forEach(b => b.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  btn.classList.add('active');
}
function toggleEdit(id) {
  const form = document.getElementById('edit-' + id);
  form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
</body>
</html>