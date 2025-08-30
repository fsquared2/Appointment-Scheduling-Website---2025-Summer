<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';
require_once '../classes/AppointmentStatus.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'provider') {
    header('Location: login.php');
    exit;
}

$status = new AppointmentStatus();
$id = $_GET['id'] ?? 0;
$row = $status->getTitle($pdo, $id);

if (!$row) {
    echo "<p>Appointment not found</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($status->setStatus($pdo, $id, 2)) { 
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Complete Appointment</title></head>
<body>
<h1>Complete Appointment</h1>
<p>Mark <strong><?= escape($row['title']) ?></strong> as completed?</p>
<form method="post">
  <button>Yes</button>
  <a href="home.php">Cancel</a>
</form>
</body>
</html>
