<?php

require_once '../includes/auth.php';

requireRole('employee');

require_once '../config/db.php';

$name = $_SESSION['name'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare(
    'SELECT
        COUNT(*) AS total_tickets,
        SUM(status = "Open") AS open_tickets,
        SUM(status = "Resolved") AS resolved_tickets
     FROM tickets
     WHERE user_id = ?'
);

$stmt->bind_param('i', $userId);
$stmt->execute();

$result = $stmt->get_result();
$stats = $result->fetch_assoc();

$stmt->close();

$totalTickets = (int) ($stats['total_tickets'] ?? 0);
$openTickets = (int) ($stats['open_tickets'] ?? 0);
$resolvedTickets = (int) ($stats['resolved_tickets'] ?? 0);


$stmt = $conn->prepare(
    'SELECT id, issue_type, priority, status
     FROM tickets
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 5'
);

$stmt->bind_param('i', $userId);
$stmt->execute();

$recentTickets = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Request Portal - Employee</title>

    <link rel="stylesheet" href="../assets/css/employee.css">
</head>
<body>

<div class="header">
    <div>
        <h1>🖥️ +IT Support Request Portal</h1>
        <p>Internal IT Helpdesk System</p>
    </div>

    <button onclick="window.location.href='../auth/logout.php'">
        Logout
    </button>
</div>

<div class="container">

<div class="stats">

    <div class="stat-box">
        <h3><?= $totalTickets ?></h3>
        <p>Total Tickets</p>
    </div>

    <div class="stat-box">
        <h3><?= $openTickets ?></h3>
        <p>Open Tickets</p>
    </div>

    <div class="stat-box">
        <h3><?= $resolvedTickets ?></h3>
        <p>Resolved Tickets</p>
    </div>

</div>

    <div class="dashboard-card">

        <h2>Employee Dashboard / Overview</h2>

        <p class="welcome">
            Welcome, <?= htmlspecialchars($name) ?> 👋
        </p>

        <div class="dashboard-actions">

            <a href="create-ticket.php" class="action-btn">
                Submit New IT Request
            </a>

            <a href="tickets.php" class="action-btn secondary">
                View My Tickets
            </a>

        </div>

        <div class="recent-tickets">

            <h3>Recent Tickets</h3>

            <div class="table-wrapper">

                <table>
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Issue</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($recentTickets->num_rows > 0): ?>

                            <?php while ($ticket = $recentTickets->fetch_assoc()): ?>

                                <tr>
                                    <td>#<?= htmlspecialchars($ticket['id']) ?></td>

                                    <td>
                                        <?= htmlspecialchars($ticket['issue_type']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($ticket['priority']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($ticket['status']) ?>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4">No tickets found.</td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>