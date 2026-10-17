<?php
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['student_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email and password.';
    } else {
        $stmt = db()->prepare('SELECT id, password_hash FROM students WHERE email = ?');
        $stmt->execute([$email]);
        $student = $stmt->fetch();
        if ($student && password_verify($password, $student['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['student_id'] = (int)$student['id'];
            header('Location: dashboard.php'); exit;
        }
        $error = 'Email or password is incorrect.';
    }
}
page_header('Sign in');
?>
<section class="card auth-card"><div class="eyebrow">STUDENT ACCOUNT</div><h1>Welcome back</h1><p class="muted">Sign in with your student email.</p>
<?php if ($error): ?><div class="alert" role="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" action="login.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label for="email">Student email</label><input id="email" name="email" type="email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>"><label for="password">Password</label><div class="password-wrap"><input id="password" name="password" type="password" autocomplete="current-password" required><button class="reveal" type="button" data-toggle-password="password">Show</button></div><button class="button full" type="submit">Sign in <span>↗</span></button></form>
<p class="switch">New student? <a href="register.php">Create an account</a></p></section>
<?php page_footer(); ?>
