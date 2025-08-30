<?php
require_once '../config.php';
require_once '../src/db_connect.php';
require_once '../common.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') $errors[] = 'Email required';
    if ($password === '') $errors[] = 'Password required';

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id, email, full_name, role, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'name'  => $user['full_name'],
                'role'  => $user['role']
            ];
            header('Location: home.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-wrap">
  <h1>Login</h1>

  <?php if ($errors): ?>
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= escape($e) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post">
    <p>
      <label>Email</label>
      <input type="email" name="email" value="<?= escape($email) ?>">
    </p>
    <p>
      <label>Password</label>
      <input type="password" name="password">
    </p>
    <p><button>Login</button></p>
  </form>

  <p class="small">No account? <a href="register.php">Register here</a></p>
</div>

<?php require_once '../partials/footer.php'; ?>
