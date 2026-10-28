<?php
// fee_management.php - Fee Management Page
// ============================================

require_once 'config.php';
checkLogin();

$success = '';
$error   = '';

// ============================================
// Handle: Add New Fee Record
// ============================================
if (isset($_POST['action']) && $_POST['action'] === 'add_fee') {

    $student_id   = (int)$_POST['student_id'];
    $amount       = (float)$_POST['amount'];
    $payment_date = trim(mysqli_real_escape_string($conn, $_POST['payment_date']));
    $status       = trim(mysqli_real_escape_string($conn, $_POST['status']));

    if ($student_id <= 0 || $amount <= 0 || empty($payment_date)) {
        $error = "⚠️ Please fill all fields with valid values.";
    } else {
        $insert = "INSERT INTO fees (student_id, amount, payment_date, status) 
                   VALUES ($student_id, $amount, '$payment_date', '$status')";
        if (mysqli_query($conn, $insert)) {
            $success = "✅ Fee record added successfully!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}

// ============================================
// Handle: Update Fee Status
// ============================================
if (isset($_GET['update_status'])) {
    $fee_id    = (int)$_GET['fee_id'];
    $new_status = ($_GET['update_status'] === 'Paid') ? 'Paid' : 'Pending';

    mysqli_query($conn, "UPDATE fees SET status = '$new_status' WHERE fee_id = $fee_id");
    $success = "✅ Payment status updated to $new_status!";
}

// ============================================
// Handle: Delete Fee Record
// ============================================
if (isset($_GET['delete_fee'])) {
    $fee_id = (int)$_GET['delete_fee'];
    mysqli_query($conn, "DELETE FROM fees WHERE fee_id = $fee_id");
    $success = "✅ Fee record deleted!";
}

// ---- Filter ----
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where  = '';
if ($filter === 'pending') $where = "WHERE f.status = 'Pending'";
if ($filter === 'paid')    $where = "WHERE f.status = 'Paid'";

// ---- Fetch Fee Records ----
$fees = mysqli_query($conn,
    "SELECT f.*, s.name as student_name 
     FROM fees f 
     JOIN students s ON f.student_id = s.student_id
     $where
     ORDER BY f.fee_id DESC"
);

// ---- Stats ----
$total_paid    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as t FROM fees WHERE status='Paid'"))['t'] ?? 0;
$total_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as t FROM fees WHERE status='Pending'"))['t'] ?? 0;

// ---- All students for dropdown ----
$students = mysqli_query($conn, "SELECT student_id, name FROM students ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Management - Hostel Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <div class="topbar">
            <div class="flex gap-1">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </button>
                <span class="page-title">💰 Fee Management</span>
            </div>
            <div class="admin-info">
                <div class="avatar">A</div>
                <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            </div>
        </div>

        <div class="content">

            <div class="page-header">
                <h1>Fee Management</h1>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Fee Summary Cards -->
            <div class="stats-grid" style="grid-template-columns:repeat(2,1fr); max-width:500px; margin-bottom:24px;">
                <div class="stat-card green">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <div class="number" style="font-size:1.4rem;">₹<?= number_format($total_paid, 0) ?></div>
                        <div class="label">Total Paid</div>
                    </div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info">
                        <div class="number" style="font-size:1.4rem;">₹<?= number_format($total_pending, 0) ?></div>
                        <div class="label">Total Pending</div>
                    </div>
                </div>
            </div>

            <!-- Add Fee + Filter Row -->
            <div class="grid-2">

                <!-- Add Fee Form -->
                <div class="card">
                    <div class="card-header"><h2>➕ Add Fee Record</h2></div>
                    <div class="card-body">
                        <form method="POST" action="fee_management.php">
                            <input type="hidden" name="action" value="add_fee">

                            <div class="form-group">
                                <label>Select Student *</label>
                                <select name="student_id" required>
                                    <option value="">-- Choose Student --</option>
                                    <?php while ($s = mysqli_fetch_assoc($students)): ?>
                                    <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount (₹) *</label>
                                <input type="number" name="amount" placeholder="e.g. 5000" min="1" step="0.01" required>
                            </div>

                            <div class="form-group">
                                <label>Date *</label>
                                <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Status *</label>
                                <select name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">➕ Add Fee</button>
                        </form>
                    </div>
                </div>

                <!-- Filter Fees -->
                <div class="card">
                    <div class="card-header"><h2>🔍 Filter Records</h2></div>
                    <div class="card-body">
                        <p class="text-muted mb-2" style="font-size:0.85rem;">Filter fee records by payment status:</p>
                        <div class="flex gap-1 flex-wrap">
                            <a href="fee_management.php" 
                               class="btn <?= $filter === 'all' ? 'btn-primary' : 'btn-info' ?>">
                               📋 All Fees
                            </a>
                            <a href="fee_management.php?filter=pending" 
                               class="btn <?= $filter === 'pending' ? 'btn-primary' : 'btn-warning' ?>">
                               ⏳ Pending Only
                            </a>
                            <a href="fee_management.php?filter=paid" 
                               class="btn <?= $filter === 'paid' ? 'btn-primary' : 'btn-success' ?>">
                               ✅ Paid Only
                            </a>
                        </div>

                        <div class="mt-3" style="padding:16px; background:#fce4ec; border-radius:var(--radius);">
                            <p style="font-size:0.83rem; color:var(--primary-dark); font-weight:600;">💡 Quick Tips:</p>
                            <ul style="font-size:0.8rem; color:var(--text); padding-left:18px; margin-top:6px; line-height:1.8;">
                                <li>Click "Mark Paid" to update a pending fee</li>
                                <li>Click "Mark Pending" to reverse a paid fee</li>
                                <li>Use the filter buttons to find pending fees</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div><!-- end grid-2 -->

            <!-- Fee Records Table -->
            <div class="card">
                <div class="card-header">
                    <h2>💰 Fee Records 
                        <?php if ($filter !== 'all'): ?>
                            - <?= ucfirst($filter) ?> Only
                        <?php endif; ?>
                    </h2>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($fees) > 0):
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($fees)):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><strong><?= htmlspecialchars($row['student_name']) ?></strong></td>
                            <td style="font-weight:700; color:var(--primary);">₹<?= number_format($row['amount'], 2) ?></td>
                            <td><?= $row['payment_date'] ?></td>
                            <td>
                                <span class="badge <?= $row['status'] === 'Paid' ? 'badge-success' : 'badge-warning' ?>">
                                    <?= $row['status'] === 'Paid' ? '✅ Paid' : '⏳ Pending' ?>
                                </span>
                            </td>
                            <td>
                                <!-- Toggle Status Button -->
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <a href="fee_management.php?update_status=Paid&fee_id=<?= $row['fee_id'] ?>&filter=<?= $filter ?>"
                                       class="btn btn-success btn-sm">Mark Paid ✅</a>
                                <?php else: ?>
                                    <a href="fee_management.php?update_status=Pending&fee_id=<?= $row['fee_id'] ?>&filter=<?= $filter ?>"
                                       class="btn btn-warning btn-sm">Mark Pending ⏳</a>
                                <?php endif; ?>

                                <!-- Delete -->
                                <a href="fee_management.php?delete_fee=<?= $row['fee_id'] ?>&filter=<?= $filter ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this fee record?')">🗑️</a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="6" class="no-data">No fee records found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
</script>
</body>
</html>
