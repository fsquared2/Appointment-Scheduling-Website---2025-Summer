<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';
require_once '../classes/AppointmentEdit.php';

if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$appointment = new AppointmentEdit();
$user = $_SESSION['user'];
$id = $_GET['id'] ?? 0;

$row = $appointment->getById($pdo, $id);
if (!$row) { echo "<p>Appointment not found</p>"; exit; }

// permission 
$canEdit = ($user['role'] === 'admin')
        || ($user['role'] === 'patient'  && $row['created_by']  == $user['id'])
        || ($user['role'] === 'provider' && $row['assigned_to'] == $user['id']);
if (!$canEdit) { echo "<p>Not allowed.</p>"; exit; }

$errors = [];
$title = $row['title'];
$notes = $row['notes'];
$start = $row['start_at'];
$end   = $row['end_at'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $start = trim($_POST['start_at'] ?? '');
    $end   = trim($_POST['end_at'] ?? '');

    if ($title === '' || $start === '' || $end === '') $errors[] = 'All fields are required';

    $startTs = strtotime($start);
    $endTs   = strtotime($end);
    if ($startTs === false || $endTs === false) $errors[] = 'Invalid date/time';
    if ($startTs !== false && $endTs !== false && $endTs <= $startTs) $errors[] = 'End time must be after start time';

    if (!$errors) {
        if ($appointment->update($pdo, $id, $title, $notes, $start, $end)) {
            header('Location: home.php'); exit;
        } else {
            $errors[] = 'Could not update appointment';
        }
    }
}

$page_title = "Edit Appointment";
require_once '../partials/header_form.php';  
?>

<h1>Edit Appointment</h1>
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
  <p><button>Save</button> <a href="home.php">Back</a></p>
</form>

<?php require_once '../partials/footer.php'; ?>
