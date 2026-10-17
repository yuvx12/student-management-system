<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Secure cookies require HTTPS in production. Keep HttpOnly and SameSite enabled locally too.
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
    session_start();
}
require_once __DIR__ . '/database.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf_token'])) {
        http_response_code(400); exit('Invalid request token. Go back and try again.');
    }
}
function require_login(): void {
    if (empty($_SESSION['student_id'])) { header('Location: login.php'); exit; }
}
function current_student(): array {
    require_login();
    $stmt = db()->prepare('SELECT id, full_name, email, student_number, course, phone, created_at FROM students WHERE id = ?');
    $stmt->execute([$_SESSION['student_id']]);
    $student = $stmt->fetch();
    if (!$student) { $_SESSION = []; session_destroy(); header('Location: login.php'); exit; }
    return $student;
}
function page_header(string $title): void { ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($title) ?> · Campus</title><link rel="stylesheet" href="assets/style.css"><script src="assets/app.js" defer></script></head><body>
<header class="topbar"><a class="brand" href="index.php"><span class="brand-mark">C</span> campus</a><span class="top-note">STUDENT PORTAL</span></header><main class="page">
<?php }
function page_footer(): void { ?></main><footer class="footer">© <?= date('Y') ?> Campus · Student portal</footer></body></html><?php }
