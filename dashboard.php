<?php
// dashboard.php - Main Dashboard Page
require_once 'config.php';
checkLogin();

$total_students     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM students"))['count'];
$total_rooms        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms"))['count'];
$occupied_rooms     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms WHERE occupied >= capacity"))['count'];
$pending_fees       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM fees WHERE status = 'Pending'"))['count'];
$pending_amount_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM fees WHERE status = 'Pending'"));
$pending_amount     = $pending_amount_row['total'] ?? 0;
$recent_students    = mysqli_query($conn, "SELECT * FROM students ORDER BY student_id DESC LIMIT 5");
$recent_fees        = mysqli_query($conn, "SELECT f.*, s.name FROM fees f JOIN students s ON f.student_id = s.student_id ORDER BY f.fee_id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hostel Management</title>
    <link rel="stylesheet" href="\hostel\style.css">
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
                <span class="page-title">📊 Dashboard</span>
            </div>
            <div class="admin-info">
                <div class="avatar">A</div>
                <span>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            </div>
        </div>

        <div class="content">

            <!-- Stat Cards -->
            <div class="stats-grid">
                <div class="stat-card pink">
                    <div class="stat-icon">👩‍🎓</div>
                    <div class="stat-info">
                        <div class="number"><?= $total_students ?></div>
                        <div class="label">Total Students</div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon">🚪</div>
                    <div class="stat-info">
                        <div class="number"><?= $total_rooms ?></div>
                        <div class="label">Total Rooms</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">🏠</div>
                    <div class="stat-info">
                        <div class="number"><?= $occupied_rooms ?></div>
                        <div class="label">Occupied Rooms</div>
                    </div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <div class="number"><?= $pending_fees ?></div>
                        <div class="label">Pending Fees</div>
                    </div>
                </div>
            </div>

            <!-- Quick Info Row -->
            <div class="grid-2 mb-2">
                <div class="card" style="margin-bottom:0">
                    <div class="card-body text-center">
                        <div style="font-size:2rem;">🏠</div>
                        <div style="font-size:1.5rem; font-weight:800; color:var(--danger);">
                           
                             <?= $total_rooms - $occupied_rooms ?>
                        </div>
                        <div class="text-muted" style="font-size:0.8rem;">Available rooms</div>
                    </div>
                </div>
                <div class="card" style="margin-bottom:0">
                    <div class="card-body text-center">
                        <div style="font-size:2rem;">💸</div>
                        <div style="font-size:1.5rem; font-weight:800; color:var(--success);">                          
                             ₹<?= number_format($pending_amount, 0) ?>
                        </div>
                        <div class="text-muted" style="font-size:0.8rem;">Pending Amouns</div>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="grid-2">

                <div class="card">
                    <div class="card-header">
                        <h2>👩‍🎓 Recent Students</h2>
                        <a href="view_students.php" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;">View All</a>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr><th>#</th><th>Name</th><th>Phone</th></tr>
                            </thead>
                            <tbody>
                            <?php if (mysqli_num_rows($recent_students) > 0):
                                $i = 1; while ($row = mysqli_fetch_assoc($recent_students)): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['phone']) ?></td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="3" class="no-data">No students added yet.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>💰 Recent Fee Records</h2>
                        <a href="fee_management.php" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;">View All</a>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr><th>Student</th><th>Amount</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                            <?php if (mysqli_num_rows($recent_fees) > 0):
                                while ($row = mysqli_fetch_assoc($recent_fees)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td>₹<?= number_format($row['amount'], 0) ?></td>
                                    <td>
                                        <span class="badge <?= $row['status'] == 'Paid' ? 'badge-success' : 'badge-warning' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="3" class="no-data">No fee records yet.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header"><h2>⚡ Quick Actions</h2></div>
                <div class="card-body flex flex-wrap gap-1">
                    <a href="add_student.php"     class="btn btn-primary">➕ Add Student</a>
                    <a href="room_management.php" class="btn btn-info">🚪 Manage Rooms</a>
                    <a href="fee_management.php"  class="btn btn-success">💰 Manage Fees</a>
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