<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Appointment' ?></title>
  <link rel="stylesheet" href="../assets/css/form.css">
</head>
<body>
