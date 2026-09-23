<?php

require_once '../includes/auth.php';

requireRole('employee');

require_once '../config/db.php';

$name = $_SESSION['name'];
$userId = $_SESSION['user_id'];

$ticketId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$ticketId) {
    http_response_code(400);
    exit('Invalid ticket.');
}

$stmt = $conn->prepare(
    'SELECT id, emp_name, emp_id, department, issue_type, priority, contact, description, status, created_at
     FROM tickets
     WHERE id = ? AND user_id = ?'
);

$stmt->bind_param('ii', $ticketId, $userId);
$stmt->execute();

$result = $stmt->get_result();

$ticket = $result->fetch_assoc();

$stmt->close();

if (!$ticket) {
    http_response_code(404);
    exit('Ticket not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?= htmlspecialchars($ticket['id']) ?></title>

    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>

<div class="header">
    <div>
        <h1>🖥️ +IT Support Request Portal</h1>
        <p>Internal IT Helpdesk System</p>
    </div>

        <form method="POST" action="../auth/logout.php">
            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"
            >
            <button type="submit" class="action-btn secondary">
                Logout
            </button>
        </form>
</div>

<div class="container">

    <div class="dashboard-card">

        <h2>
            Ticket #<?= htmlspecialchars($ticket['id']) ?>
        </h2>

        <div class="ticket-details">

            <div class="ticket-detail">
                <strong>Employee Name</strong>
                <span><?= htmlspecialchars($ticket['emp_name']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Employee ID</strong>
                <span><?= htmlspecialchars($ticket['emp_id']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Department</strong>
                <span><?= htmlspecialchars($ticket['department']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Issue Type</strong>
                <span><?= htmlspecialchars($ticket['issue_type']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Priority</strong>
                <span><?= htmlspecialchars($ticket['priority']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Contact Number</strong>
                <span><?= htmlspecialchars($ticket['contact']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Status</strong>
                <span><?= htmlspecialchars($ticket['status']) ?></span>
            </div>

            <div class="ticket-detail">
                <strong>Created</strong>
                <span><?= htmlspecialchars($ticket['created_at']) ?></span>
            </div>

            <div class="ticket-detail full-width">
                <strong>Issue Description</strong>
                <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
            </div>

        </div>

        <a href="tickets.php" class="action-btn secondary">
            Back to My Tickets
        </a>

    </div>

</div>

</body>
</html>