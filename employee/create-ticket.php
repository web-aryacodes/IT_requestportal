<?php

require_once '../includes/auth.php';

requireRole('employee');

require_once '../config/db.php';

$name = $_SESSION['name'];
$userId = $_SESSION['user_id'];

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $employeeId = trim($_POST['employee_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $issueType = trim($_POST['issue_type'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $errors[] = 'Invalid security token. Please try again.';
    }

    if ($employeeId === '') {
        $errors[] = 'Employee ID is required.';
    }

    $allowedDepartments = ['IT', 'HR', 'Finance', 'Sales', 'Operations'];

    if (!in_array($department, $allowedDepartments, true)) {
        $errors[] = 'Select a valid department.';
    }

    $allowedIssueTypes = ['Hardware', 'Software', 'Network', 'Email', 'Other'];

    if (!in_array($issueType, $allowedIssueTypes, true)) {
        $errors[] = 'Select a valid issue type.';
    }

    if (!in_array($priority, ['Low', 'Medium', 'High'], true)) {
        $errors[] = 'Select a valid priority.';
    }

    if (!preg_match('/^[0-9]{10}$/', $contact)) {
        $errors[] = 'Enter a valid 10-digit contact number.';
    }

    if ($description === '') {
        $errors[] = 'Issue description is required.';
    }

    if (empty($errors)) {

        $stmt = $conn->prepare(
            'INSERT INTO tickets
            (user_id, emp_name, emp_id, department, issue_type, priority, contact, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'isssssss',
            $userId,
            $name,
            $employeeId,
            $department,
            $issueType,
            $priority,
            $contact,
            $description
        );

        if ($stmt->execute()) {
            $success = 'IT request submitted successfully.';

            $employeeId = '';
            $department = '';
            $issueType = '';
            $priority = '';
            $contact = '';
            $description = '';
        } else {
            $errors[] = 'Unable to submit your request. Please try again.';
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
    <title>Submit IT Request</title>

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

    <div class="form-card">

        <h2>📋 Submit New IT Request</h2>

        <?php if (!empty($errors)): ?>

            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <?php if ($success): ?>

            <div class="success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
            >

            <div class="form-grid">

                <div class="form-group">
                    <label>Employee Name</label>
                    <input
                        type="text"
                        value="<?= htmlspecialchars($name) ?>"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label>Employee ID</label>
                    <input
                        type="text"
                        name="employee_id"
                        value="<?= htmlspecialchars($employeeId ?? '') ?>"
                        placeholder="e.g. NSE1042"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select name="department" required>
                        <option value="">Select Department</option>
                        <option value="IT" <?= ($department ?? '') === 'IT' ? 'selected' : '' ?>>IT</option>
                        <option value="HR" <?= ($department ?? '') === 'HR' ? 'selected' : '' ?>>HR</option>
                        <option value="Finance" <?= ($department ?? '') === 'Finance' ? 'selected' : '' ?>>Finance</option>
                        <option value="Sales" <?= ($department ?? '') === 'Sales' ? 'selected' : '' ?>>Sales</option>
                        <option value="Operations" <?= ($department ?? '') === 'Operations' ? 'selected' : '' ?>>Operations</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Issue Type</label>
                    <select name="issue_type" required>
                        <option value="">Select Issue</option>
                        <option value="Hardware" <?= ($issueType ?? '') === 'Hardware' ? 'selected' : '' ?>>Hardware</option>
                        <option value="Software" <?= ($issueType ?? '') === 'Software' ? 'selected' : '' ?>>Software</option>
                        <option value="Network" <?= ($issueType ?? '') === 'Network' ? 'selected' : '' ?>>Network</option>
                        <option value="Email" <?= ($issueType ?? '') === 'Email' ? 'selected' : '' ?>>Email</option>
                        <option value="Other" <?= ($issueType ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority" required>
                        <option value="">Select Priority</option>
                        <option value="Low" <?= ($priority ?? '') === 'Low' ? 'selected' : '' ?>>Low</option>
                        <option value="Medium" <?= ($priority ?? '') === 'Medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="High" <?= ($priority ?? '') === 'High' ? 'selected' : '' ?>>High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input
                        type="text"
                        name="contact"
                        value="<?= htmlspecialchars($contact ?? '') ?>"
                        placeholder="Enter contact number"
                        maxlength="10"
                        required
                    >
                </div>

                <div class="form-group full-width">
                    <label>Issue Description</label>
                    <textarea
                        name="description"
                        placeholder="Describe your issue in detail..."
                        required
                    ><?= htmlspecialchars($description ?? '') ?></textarea>
                </div>

            </div>

            <button class="btn-submit" type="submit">
                Submit Request
            </button>

        </form>

    </div>

        <a href="dashboard.php" class="action-btn secondary">
            ← Back to Dashboard
        </a>

</div>

</body>
</html>