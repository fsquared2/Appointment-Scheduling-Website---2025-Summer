<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';
require_once '../classes/AppointmentList.php';

if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$user = $_SESSION['user'];
$term = trim($_GET['q'] ?? '');

$list = new AppointmentList();
$rows = $list->getAppointments($pdo, $user['id'], $user['role'], $term);

$page_title = 'Appointments Home';
require_once '../partials/header.php';
?>

<h1>Welcome, <?= escape($user['name']) ?></h1>

<form method="get">
  <input type="text" name="q" value="<?= escape($term) ?>" placeholder="Search appointments">
  <button>Search</button>
  <a href="create.php">New Appointment</a>
  <a href="logout.php">Logout</a>
</form>

<?php if (!$rows): ?>
  <p>No appointments found.</p>
<?php else: ?>
  <?php foreach ($rows as $row): ?>
    <div class="appointment">
      <h3><?= escape($row['title']) ?></h3>
      <p><strong>Time:</strong> <?= escape($row['start_at']) ?> → <?= escape($row['end_at']) ?></p>
      <p><strong>Status:</strong> <?= escape($row['status_name']) ?></p>
      <p><strong>Patient:</strong> <?= escape($row['patient']) ?> |
         <strong>Provider:</strong> <?= escape($row['provider']) ?></p>
      <p>
        <a href="edit.php?id=<?= escape($row['id']) ?>">Edit</a> |
        <a href="delete.php?id=<?= escape($row['id']) ?>">Delete</a>
        <?php if ($user['role'] === 'provider'): ?>
          | <a href="complete.php?id=<?= escape($row['id']) ?>">Complete</a>
        <?php endif; ?>
        <?php if ($user['role'] === 'admin'): ?>
          | <a href="confirm.php?id=<?= escape($row['id']) ?>">Confirm</a>
          | <a href="cancel.php?id=<?= escape($row['id']) ?>">Cancel</a>
        <?php endif; ?>
      </p>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php require_once '../partials/footer.php'; ?>
