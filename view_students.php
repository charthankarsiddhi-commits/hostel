<?php
// view_students.php - View All Students
// ============================================

require_once 'config.php';
checkLogin();

$success = '';
$error   = '';

// ---- Search Functionality ----
$search = '';
$where  = '';
if (!empty($_GET['search'])) {
    $search = trim(mysqli_real_escape_string($conn, $_GET['search']));
    $where  = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'";
}

// ---- Fetch Students ----
$students = mysqli_query($conn, "SELECT * FROM students $where ORDER BY student_id DESC");
$count    = mysqli_num_rows($students);

// Check for success message passed from delete/edit
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'deleted') $success = "✅ Student deleted successfully!";
    if ($_GET['msg'] === 'updated') $success = "✅ Student updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Hostel Management</title>
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
                <span class="page-title">👩‍🎓 All Students</span>
            </div>
            <div class="admin-info">
                <div class="avatar">A</div>
                <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            </div>
        </div>

        <div class="content">

            <div class="page-header">
                <h1>All Students <span style="font-size:1rem; color:var(--text-light);">(<?= $count ?> found)</span></h1>
                <a href="add_student.php" class="btn btn-primary">➕ Add New Student</a>
            </div>

            <!-- Messages -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Search Bar -->
            <div class="card">
                <div class="card-body" style="padding:16px 20px;">
                    <form method="GET" action="view_students.php">
                        <div class="flex gap-1 flex-wrap">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="🔍 Search by name, email, or phone..."
                                value="<?= htmlspecialchars($search) ?>"
                                style="flex:1; min-width:200px; padding:10px 14px; 
                                       border:1.5px solid var(--border); border-radius:var(--radius);
                                       font-family:inherit; font-size:0.88rem; outline:none;">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($search)): ?>
                                <a href="view_students.php" class="btn btn-warning">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Students Table -->
            <div class="card">
                <div class="card-header">
                    <h2>👩‍🎓 Student List</h2>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($count > 0): ?>
                            <?php $i = 1; while ($row = mysqli_fetch_assoc($students)): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td style="max-width:180px; word-break:break-word;"><?= htmlspecialchars($row['address']) ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <a href="edit_student.php?id=<?= $row['student_id'] ?>" 
                                       class="btn btn-warning btn-sm">✏️ Edit</a>

                                    <!-- Delete Button (with confirmation) -->
                                    <a href="delete_student.php?id=<?= $row['student_id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($row['name']) ?>? This will also remove all their fee and complaint records.')">
                                       🗑️ Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-data">
                                    <?= !empty($search) ? "No students found for '$search'." : "No students added yet. <a href='add_student.php'>Add one now →</a>" ?>
                                </td>
                            </tr>
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
