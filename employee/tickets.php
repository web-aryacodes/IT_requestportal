<?php

require_once '../includes/auth.php';

requireRole('employee');

require_once '../config/db.php';

$name = $_SESSION['name'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare(
    'SELECT id, issue_type, priority, status, created_at
     FROM tickets
     WHERE user_id = ?
     ORDER BY created_at DESC'
);

$stmt->bind_param('i', $userId);
$stmt->execute();

$result = $stmt->get_result();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>

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

    <div class="dashboard-card">
     
        <h2>My Tickets</h2>

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>Issue</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($ticket = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    #<?= htmlspecialchars($ticket['id']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($ticket['issue_type']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($ticket['priority']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($ticket['status']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($ticket['created_at']) ?>
                                </td>

                                <td>
                                    <a
                                        href="ticket.php?id=<?= urlencode($ticket['id']) ?>"
                                        class="ticket-link"
                                    >
                                        View
                                    </a>
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
        
        </div>

        <a href="dashboard.php" class="action-btn secondary">
            ← Back to Dashboard
        </a>
        
    </div>

</div>

</body>
</html>