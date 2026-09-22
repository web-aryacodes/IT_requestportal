<?php

require_once '../includes/auth.php';
requireRole('admin');

require_once '../config/db.php';

$filter = $_GET['filter'] ?? 'all';

if (!in_array($filter, ['all', 'open', 'resolved'], true)) {
    $filter = 'all';
}

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

if ($filter === 'all') {
    $stmt = $conn->prepare(
        'SELECT id, emp_name, emp_id, department, issue_type, priority, contact, description, status, created_at
         FROM tickets
         ORDER BY created_at DESC'
    );
} else {
    $status = $filter === 'open' ? 'Open' : 'Resolved';

    $stmt = $conn->prepare(
        'SELECT id, emp_name, emp_id, department, issue_type, priority, contact, description, status, created_at
         FROM tickets
         WHERE status = ?
         ORDER BY created_at DESC'
    );

    $stmt->bind_param('s', $status);
}

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

                <a
                    href="dashboard.php?filter=all"
                    class="action-btn <?php echo $filter === 'all' ? '' : 'secondary'; ?>"
                >
                    All
                </a>

                <a
                    href="dashboard.php?filter=open"
                    class="action-btn <?php echo $filter === 'open' ? '' : 'secondary'; ?>"
                >
                    Open
                </a>

                <a
                    href="dashboard.php?filter=resolved"
                    class="action-btn <?php echo $filter === 'resolved' ? '' : 'secondary'; ?>"
                >
                    Resolved
                </a>

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
                                        <button
                                            type="button"
                                            class="ticket-view"
                                            data-ticket-id="<?php echo (int) $ticket['id']; ?>"
                                            data-employee-name="<?php echo htmlspecialchars($ticket['emp_name'], ENT_QUOTES); ?>"
                                            data-employee-id="<?php echo htmlspecialchars($ticket['emp_id'], ENT_QUOTES); ?>"
                                            data-department="<?php echo htmlspecialchars($ticket['department'], ENT_QUOTES); ?>"
                                            data-issue-type="<?php echo htmlspecialchars($ticket['issue_type'], ENT_QUOTES); ?>"
                                            data-priority="<?php echo htmlspecialchars($ticket['priority'], ENT_QUOTES); ?>"
                                            data-contact="<?php echo htmlspecialchars($ticket['contact'], ENT_QUOTES); ?>"
                                            data-description="<?php echo htmlspecialchars($ticket['description'], ENT_QUOTES); ?>"
                                            data-status="<?php echo htmlspecialchars($ticket['status'], ENT_QUOTES); ?>"
                                            data-created="<?php echo htmlspecialchars($ticket['created_at'], ENT_QUOTES); ?>"
                                        >
                                            View
                                        </button>
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

    <div class="ticket-modal" id="ticketModal">
        <div class="ticket-modal-content">
            <button type="button" class="ticket-modal-close" id="ticketModalClose">
                ×
            </button>

            <h2>Ticket Details</h2>

            <div class="ticket-details">

                <div class="ticket-detail">
                    <strong>Ticket #</strong>
                    <span id="modalTicketId"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Employee Name</strong>
                    <span id="modalEmployeeName"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Employee ID</strong>
                    <span id="modalEmployeeId"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Department</strong>
                    <span id="modalDepartment"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Issue Type</strong>
                    <span id="modalIssueType"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Priority</strong>
                    <span id="modalPriority"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Contact Number</strong>
                    <span id="modalContact"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Status</strong>
                    <span id="modalStatus"></span>
                </div>

                <div class="ticket-detail">
                    <strong>Created</strong>
                    <span id="modalCreated"></span>
                </div>

                <div class="ticket-detail full-width">
                    <strong>Issue Description</strong>
                    <p id="modalDescription"></p>
                </div>

            </div>
        </div>
    </div>

        <script src="../assets/js/admin.js"></script>
</body>
</html>