<?php

require_once __DIR__ . '/session.php';

function requireLogin(): void
{
    if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
        header('Location: ../auth/login.php');
        exit;
    }

    $sessionTimeout = 1800;

    if (
        isset($_SESSION['last_activity']) &&
        (time() - $_SESSION['last_activity']) > $sessionTimeout
    ) {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: ../auth/login.php');
        exit;
    }

    $_SESSION['last_activity'] = time();
}

function requireRole(string $role): void
{
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}