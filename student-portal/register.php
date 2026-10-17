<?php
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['student_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $number = trim($_POST['student_number'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    if (mb_strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $number === '' || $course === '' || strlen($password) < 8) {
        $error = 'Fill in all required fields. Password must be at least 8 characters.';
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO students (full_name,email,student_number,course,phone,password_hash) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$name,$email,$number,$course,$phone ?: null,password_hash($password, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['student_id'] = (int)db()->lastInsertId();
            header('Location: dashboard.php'); exit;
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') $error = 'That email or student number is already registered.';
            else throw $ex;
        }
    }
}
page_header('Create account');
?>
<section class="card auth-card register-card"><div class="eyebrow">STUDENT ACCOUNT</div><h1>Create your account</h1><p class="muted">Enter your details to set up your student profile.</p>
<?php if ($error): ?><div class="alert" role="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" action="register.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label for="full_name">Full name</label><input id="full_name" name="full_name" autocomplete="name" required maxlength="120" value="<?= e($_POST['full_name'] ?? '') ?>"><label for="email">Student email</label><input id="email" name="email" type="email" autocomplete="email" required maxlength="190" value="<?= e($_POST['email'] ?? '') ?>"><label for="student_number">Student number</label><input id="student_number" name="student_number" required maxlength="40" value="<?= e($_POST['student_number'] ?? '') ?>"><label for="course">Course / program</label><input id="course" name="course" required maxlength="120" value="<?= e($_POST['course'] ?? '') ?>"><label for="phone">Phone <span class="optional">(optional)</span></label><input id="phone" name="phone" type="tel" autocomplete="tel" maxlength="30" value="<?= e($_POST['phone'] ?? '') ?>"><label for="password">Password</label><div class="password-wrap"><input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required><button class="reveal" type="button" data-toggle-password="password">Show</button></div><small class="hint">Use at least 8 characters.</small><button class="button full" type="submit">Create account <span>↗</span></button></form>
<p class="switch">Already registered? <a href="login.php">Sign in</a></p></section>
<?php page_footer(); ?>
