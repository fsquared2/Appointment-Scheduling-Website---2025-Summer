<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '') $errors[] = 'Name required';
    if ($email === '') $errors[] = 'Email required';
    if ($password === '') $errors[] = 'Password required';

    if (!$errors) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name, email, password, role) VALUES (?,?,?,?)"
            );
            $stmt->execute([$name, $email, $hash, 'patient']); 
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                $errors[] = 'Email already taken';
            } else {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-wrap">
  <h1>Register</h1>

  <?php if ($errors): ?>
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= escape($e) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post">
    <p>
      <label>Name</label>
      <input type="text" name="name" value="<?= escape($name) ?>">
    </p>
    <p>
      <label>Email</label>
      <input type="email" name="email" value="<?= escape($email) ?>">
    </p>
    <p>
      <label>Password</label>
      <input type="password" name="password">
    </p>
    <p><button>Register</button></p>
  </form>

  <p class="small">Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php require_once '../partials/footer.php'; ?>
