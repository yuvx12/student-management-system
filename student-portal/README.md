# Campus Student Portal (PHP + MySQL)

A learning project with HTML rendered by PHP, CSS in `assets/style.css`, JavaScript in `assets/app.js`, PHP pages and shared PHP helpers in `includes/`, and the schema in `database.sql`.

## Run locally with XAMPP

1. Copy the `student-portal` folder into XAMPP's `htdocs` folder.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`, choose **Import**, and import `database.sql`.
4. If your MySQL username or password differs from the defaults, update `DB_USER` and `DB_PASS` in `includes/database.php`.
5. Visit `http://localhost/student-portal/` and create a student account.

The app stores full name, email, student number, course/program, optional phone, a securely hashed password, and the registration timestamp. Email and student number must be unique. A student can sign in and view their profile and registration date.

## Security notes

Passwords are hashed with PHP `password_hash()` and checked with `password_verify()`. Database queries use PDO prepared statements, forms use CSRF tokens, and output is escaped. For a real deployment, use HTTPS, protect database credentials outside the public web root, add rate limiting and password reset, and review privacy/data retention requirements. This starter does not include administrator accounts or student profile editing.
