<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../config/db.php';

    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!in_array($role, ['admin', 'employee'], true)) {
        $errors[] = 'Select a valid role.';
    }

    if (empty($errors)) {

        $stmt = $conn->prepare(
            'SELECT id, name, email, password, role FROM users WHERE email = ?'
        );

        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } elseif ($user['role'] !== $role) {
            $errors[] = 'Invalid email or password.';
        } else {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $success = 'Login successful.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Portal - Login</title>
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

        <div class="form-title">Welcome back 👋</div>
        <div class="form-sub">Sign in to manage your IT tickets and requests.</div>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?= htmlspecialchars($errors[0]) ?>
            </div>
        <?php elseif (!empty($success)): ?>
            <div class="success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php" novalidate>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@gmail.com" autocomplete="email">
                <div class="error" id="emailError"></div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password">
                    <button type="button" class="pw-toggle" id="passwordToggle">👁</button>
                </div>
                <div class="error" id="passwordError"></div>
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="">Select</option>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>
                <div class="error" id="roleError"></div>
            </div>

            <button type="submit" class="submit">Login</button>

            <div class="helper">
                Don't have an account?
                <a href="signup.php">Create an account</a>
            </div>
        </form>
    </section>
</div>

<script src="../assets/js/auth.js"></script>
</body>
</html>