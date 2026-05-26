<?php
require_once '../private/db.php';

$user_id = 1;

try {
    $stmt = $pdo->prepare(
        "SELECT id, meal_name, image_path, logged_at
         FROM meals
         WHERE user_id = :uid
         ORDER BY logged_at DESC"
    );
    $stmt->execute([':uid' => $user_id]);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MoguMogu Meal History</title>
  <style>
    @font-face {
      font-family: "Starborn";
      src: url(/assets/fonts/Starborn.ttf);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background-image: url(/assets/images/wallpaper2.jpg);
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      font-family: sans-serif;
      padding-bottom: 40px;
    }
    #historyTitle {
      font-family: Starborn;
      font-size: 40px;
      color: #F4ADCF;
      text-align: center;
      padding: 30px 0 16px;
    }
    .tabs {
      display: flex;
      justify-content: center;
      gap: 16px;
      margin-bottom: 24px;
    }
    .tabs button {
      font-family: Starborn;
      font-size: 18px;
      padding: 8px 28px;
      border: 3px solid #F4ADCF;
      border-radius: 30px;
      background: transparent;
      color: #F4ADCF;
      cursor: pointer;
    }
    .tabs button.active { background: #F4ADCF; color: white; }
    .contentBox {
      width: 660px;
      max-width: 95vw;
      background: #FFF4BF;
      margin: 0 auto;
      border-radius: 12px;
      padding: 24px;
    }
    .tab-section { display: none; }
    .tab-section.active { display: block; }
    .cal-nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }
    .cal-nav a { font-family: Starborn; font-size: 22px; color: #e07b39; text-decoration: none; padding: 0 8px; }
    .cal-nav span { font-family: Starborn; font-size: 22px; color: #555; }
    .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
    .cal-header {
      font-family: Starborn;
      font-size: 13px;
      text-align: center;
      background: #F4ADCF;
      color: white;
      padding: 5px 0;
      border-radius: 4px;
    }
    .cal-day {
      min-height: 68px;
      padding: 5px;
      border: 1px solid #e8d5a0;
      border-radius: 6px;
      background: white;
      font-size: 0.78rem;
    }
    .cal-day.empty { background: transparent; border: none; }
    .cal-day.today { border: 2px solid #F4ADCF; }
    .cal-day.has-meal { background: #e8f5e9; }
    .cal-day .day-num { font-weight: bold; font-size: 0.88rem; }
    .cal-day .meal-label {
      display: block;
      font-size: 0.7rem;
      color: #555;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 14px; }
    .gallery-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
    .gallery-card img { width: 100%; height: 130px; object-fit: cover; display: block; }
    .gallery-card .no-photo {
      width: 100%; height: 130px;
      background: #fde8d8;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.8rem;
    }
    .gallery-card .card-body { padding: 10px; }
    .gallery-card .card-name { font-family: Starborn; font-size: 1rem; color: #555; margin-bottom: 3px; }
    .gallery-card .card-date { font-size: 0.75rem; color: #999; }
    .empty-msg { text-align: center; font-family: Starborn; font-size: 1.1rem; color: #aaa; padding: 30px 0; }
    .back-link { display: block; text-align: center; margin-top: 24px; font-family: Starborn; font-size: 1rem; color: #F4ADCF; text-decoration: none; }
  </style>
</head>
<body>

<div id="historyTitle">Meal History</div>

<div class="tabs">
  <button class="active" onclick="showTab('calendar-tab', this)">📅 Calendar</button>
  <button onclick="showTab('gallery-tab', this)">🖼️ Gallery</button>
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
      <div class="empty-msg">No meals logged yet. Go eat something! 🍜</div>
    <?php else: ?>
      <div class="gallery">
        <?php foreach ($meals as $m):
          $has_photo = $m['image_path'] && file_exists(__DIR__ . '/' . $m['image_path']);
          $display_date = date('M j, Y', strtotime($m['logged_at']));
        ?>
          <div class="gallery-card">
            <?php if ($has_photo): ?>
              <img src="<?= htmlspecialchars($m['image_path']) ?>" alt="<?= htmlspecialchars($m['meal_name']) ?>">
            <?php else: ?>
              <div class="no-photo">🍽️</div>
            <?php endif; ?>
            <div class="card-body">
              <div class="card-name"><?= htmlspecialchars($m['meal_name']) ?></div>
              <div class="card-date"><?= $display_date ?></div>
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
</script>
</body>
</html>