<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/db.php';

    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $contact = trim($_POST['contact'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];

    if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = 'Enter a valid name.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        $errors[] = 'Enter a valid email address.';
    }

    if (!preg_match('/^\d{10}$/', $contact)) {
        $errors[] = 'Enter a valid 10-digit contact number.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = 'An account with this email already exists.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Portal - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="shell">
    <aside class="brand">
        <div>
            <div class="logo">
                <div class="logo-mark">🛠</div>
                <span>IT Support Portal</span>
            </div>

            <h1>Resolve issues faster, together.</h1>

            <p class="lead">
                Submit tickets, track progress, and get help from your IT team — all from one secure dashboard.
            </p>

            <div class="features">
                <div class="feature">
                    <div class="dot">⚡</div>
                    <div>
                        <strong>Fast response</strong><br>
                        <span>Average reply under 15 minutes.</span>
                    </div>
                </div>

                <div class="feature">
                    <div class="dot">🔒</div>
                    <div>
                        <strong>Secure access</strong><br>
                        <span>Role-based admin & employee logins.</span>
                    </div>
                </div>

                <div class="feature">
                    <div class="dot">📊</div>
                    <div>
                        <strong>Live tracking</strong><br>
                        <span>Monitor every ticket in real time.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="foot">© 2026 IT Support Portal · All rights reserved</div>
    </aside>

    <section class="auth">
        <button type="button" class="back" onclick="history.back()">← Back</button>

        <div class="form-title">Create your account</div>
        <div class="form-sub">Join your team and start raising support tickets.</div>

        <form id="signupForm" method="POST" action="signup.php" novalidate>

            <div class="field">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Full name" autocomplete="name">
                <div class="error" id="nameError"></div>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@gmail.com" autocomplete="email">
                <div class="error" id="emailError"></div>
            </div>

            <div class="field">
                <label for="contact">Contact</label>
                <input type="tel" id="contact" name="contact" placeholder="10-digit number" autocomplete="tel">
                <div class="error" id="contactError"></div>
            </div>

            <div class="field">
                <label for="password">Password</label>

                <div class="input-wrap">
                    <input type="password" id="password" name="password" placeholder="Create a strong password" autocomplete="new-password">

                    <button
                        type="button"
                        class="pw-toggle"
                        id="passwordToggle"
                        aria-label="Show password"
                    >👁</button>
                </div>

                <div class="error" id="passwordError"></div>
            </div>

            <div class="field">
                <label for="role">Role</label>

                <select id="role" name="role">
                    <option value="employee">Employee</option>
                </select>

                <div class="error" id="roleError"></div>
            </div>

            <button type="submit" class="submit">Sign Up</button>

            <div class="helper">
                Already have an account?
                <a href="login.php">Login</a>
            </div>

        </form>
    </section>
</div>

<script src="../assets/js/auth.js"></script>
</body>
</html>