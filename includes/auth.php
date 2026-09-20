<?php

require_once 'session.php';

function requireLogin(): void
{
    if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
        header('Location: ../auth/login.php');
        exit;
    }
}

function requireRole(string $role): void
{
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}