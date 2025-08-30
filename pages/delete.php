<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';
require_once '../classes/AppointmentDelete.php';

if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$appointment = new AppointmentDelete();
$user = $_SESSION['user'];
$id = $_GET['id'] ?? 0;

$row = $appointment->getById($pdo, $id);
if (!$row) { echo "<p>Appointment not found</p>"; exit; }

// for permissions
$canDelete = ($user['role'] === 'admin')
          || ($user['role'] === 'patient'  && $row['created_by']  == $user['id'])
          || ($user['role'] === 'provider' && $row['assigned_to'] == $user['id']);
if (!$canDelete) { echo "<p>Not allowed.</p>"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($appointment->delete($pdo, $id)) { header('Location: home.php'); exit; }
}
?>
<!DOCTYPE html>
<html>
<head><title>Delete Appointment</title></head>
  <link rel="stylesheet" href="../assets/css/auth.css">
<body>
<h1>Delete Appointment</h1>
<p>Are you sure you want to delete: <strong><?= escape($row['title']) ?></strong>?</p>
<form method="post">
  <button>Yes, delete</button> <a href="home.php">Cancel</a>
</form>
</body>
</html>
