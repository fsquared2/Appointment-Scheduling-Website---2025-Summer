<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';
require_once '../classes/AppointmentCreate.php';

if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$user = $_SESSION['user'];
$appointment = new AppointmentCreate();

$errors = [];
$title = ''; $notes = ''; $start = ''; $end = ''; $assignedTo = '';
$providers = $appointment->getProviders($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $start = trim($_POST['start_at'] ?? '');
    $end   = trim($_POST['end_at'] ?? '');
    $assignedTo = trim($_POST['assigned_to'] ?? '');

    if ($title === '' || $start === '' || $end === '') $errors[] = 'All fields are required';

    // date validation
    $startTs = strtotime($start);
    $endTs   = strtotime($end);
    if ($startTs === false || $endTs === false) $errors[] = 'Invalid date/time';
    if ($startTs !== false && $endTs !== false && $endTs <= $startTs) $errors[] = 'End time must be after start time';

    if (!$errors) {
        if ($appointment->create($pdo, $title, $notes, $start, $end, $user['id'], $assignedTo)) {
            header('Location: home.php'); exit;
        } else {
            $errors[] = 'Could not create appointment';
        }
    }
}

$page_title = "New Appointment";
require_once '../partials/header_form.php';  
?>

<h1>New Appointment</h1>
<?php if ($errors): ?>
  <ul>
    <?php foreach($errors as $e): ?><li><?= escape($e) ?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post">
  <p>Title<br><input name="title" value="<?= escape($title) ?>"></p>
  <p>Notes<br><textarea name="notes"><?= escape($notes) ?></textarea></p>
  <p>Start<br><input type="datetime-local" name="start_at" value="<?= escape($start) ?>"></p>
  <p>End<br><input type="datetime-local" name="end_at" value="<?= escape($end) ?>"></p>
  <p>Provider<br>
    <select name="assigned_to">
      <?php foreach ($providers as $p): ?>
        <option value="<?= escape($p['id']) ?>"><?= escape($p['full_name']) ?></option>
      <?php endforeach; ?>
    </select>
  </p>
  <p><button>Create</button> <a href="home.php">Back</a></p>
</form>

<?php require_once '../partials/footer.php'; ?>
