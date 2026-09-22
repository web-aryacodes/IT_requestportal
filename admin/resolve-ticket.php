<?php

require_once '../includes/auth.php';
requireRole('admin');

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid security token.');
}

$ticketId = filter_input(INPUT_POST, 'ticket_id', FILTER_VALIDATE_INT);

if (!$ticketId) {
    http_response_code(400);
    exit('Invalid ticket.');
}

$stmt = $conn->prepare(
    'UPDATE tickets
     SET status = "Resolved"
     WHERE id = ? AND status = "Open"'
);

$stmt->bind_param('i', $ticketId);
$stmt->execute();

$stmt->close();

header('Location: dashboard.php');
exit;