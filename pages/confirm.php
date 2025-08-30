<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';
require_once '../classes/AppointmentStatus.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
    if ($status->setStatus($pdo, $id, 4)) { 
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Confirm Appointment</title>
  <link rel="stylesheet" href="../assets/css/confirm.css">
</head>
<body>
  <div class="confirm-box">
    <h1>Are you sure you want to confirm this appointment?</h1>
    <form method="post">
      <button type="submit">Yes, Confirm</button>
      <a href="home.php">Cancel</a>
    </form>
  </div>
</body>
</html>

