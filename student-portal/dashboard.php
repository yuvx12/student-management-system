<?php
require_once __DIR__ . '/includes/functions.php';
$student = current_student();
page_header('Student dashboard');
?>
<section class="card dashboard"><div class="dashboard-head"><div><div class="eyebrow">STUDENT SPACE</div><h1>Hello, <?= e(explode(' ', $student['full_name'])[0]) ?>.</h1><p class="muted">Your student profile and account details.</p></div><form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="button secondary" type="submit">Sign out</button></form></div>
<div class="profile"><div class="avatar"><?= e(strtoupper(substr($student['full_name'], 0, 1))) ?></div><div><strong><?= e($student['full_name']) ?></strong><span><?= e($student['email']) ?></span></div></div>
<dl class="details"><div><dt>Student number</dt><dd><?= e($student['student_number']) ?></dd></div><div><dt>Course / program</dt><dd><?= e($student['course']) ?></dd></div><div><dt>Phone</dt><dd><?= e($student['phone'] ?: 'Not provided') ?></dd></div><div><dt>Registered on</dt><dd><?= e(date('F j, Y', strtotime($student['created_at']))) ?></dd></div></dl>
<p class="privacy">Your profile is saved in the MySQL database.</p></section>
<?php page_footer(); ?>
