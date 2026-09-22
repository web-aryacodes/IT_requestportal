<?php

require_once '../includes/auth.php';
requireRole('admin');

require_once '../config/db.php';

$stmt = $conn->prepare(
    'SELECT
        COUNT(*) AS total_tickets,
        SUM(status = "Open") AS open_tickets,
        SUM(status = "Resolved") AS resolved_tickets
     FROM tickets'
);
$stmt->execute();

$result = $stmt->get_result();
$stats = $result->fetch_assoc();

$stmt->close();

$totalTickets = (int) ($stats['total_tickets'] ?? 0);
$openTickets = (int) ($stats['open_tickets'] ?? 0);
$resolvedTickets = (int) ($stats['resolved_tickets'] ?? 0);

$stmt = $conn->prepare(
    'SELECT id, emp_name, issue_type, priority, status, created_at
     FROM tickets
     ORDER BY created_at DESC'
);
$stmt->execute();

$tickets = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IT Support Portal</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

    <header class="header">
        <div>
            <h1>🖥️ +IT Support Request Portal</h1>
            <p>Internal IT Helpdesk System</p>
        </div>

        <a href="../auth/logout.php" class="action-btn secondary">
            Logout
        </a>
    </header>

    <main class="container">

        <section class="dashboard-card">
            <h2>Admin Dashboard / Overview</h2>

            <div class="stats">

                <div class="stat-card">
                    <strong><?php echo $totalTickets; ?></strong>
                    <span>Total<br>Tickets</span>
                </div>

                <div class="stat-card">
                    <strong><?php echo $openTickets; ?></strong>
                    <span>Open<br>Tickets</span>
                </div>

                <div class="stat-card">
                    <strong><?php echo $resolvedTickets; ?></strong>
                    <span>Resolved<br>Tickets</span>
                </div>

            </div>
        </section>

        <section class="dashboard-card recent-tickets">

            <h2>Tickets</h2>

            <div class="dashboard-actions">
                <button type="button" class="action-btn">
                    All
                </button>

                <button type="button" class="action-btn secondary">
                    Open
                </button>

                <button type="button" class="action-btn secondary">
                    Resolved
                </button>
            </div>

            <div class="table-wrapper">

                <table>
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Employee</th>
                            <th>Issue</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($tickets->num_rows > 0): ?>

                            <?php while ($ticket = $tickets->fetch_assoc()): ?>

                                <tr>
                                    <td>#<?php echo (int) $ticket['id']; ?></td>

                                    <td>
                                        <?php echo htmlspecialchars($ticket['emp_name']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($ticket['issue_type']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($ticket['priority']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </td>

                                    <td>
                                        View
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6">
                                    No tickets found.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

        </section>

    </main>

</body>
</html>