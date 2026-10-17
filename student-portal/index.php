<?php
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['student_id'])) { header('Location: dashboard.php'); exit; }
page_header('Student portal');
?>
<section class="hero"><div class="eyebrow">YOUR LEARNING, IN ONE PLACE</div><h1>Make room for<br><span>what’s next.</span></h1><p>Sign in to your student space and pick up where your goals begin.</p><div class="actions"><a class="button" href="login.php">Sign in <span>↗</span></a><a class="button secondary" href="register.php">Create account</a></div></section>
<?php page_footer(); ?>
