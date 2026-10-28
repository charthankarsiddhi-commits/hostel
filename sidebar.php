<?php
// Get current page name safely
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h2 class="logo">🏠 HostelMS</h2>
    <p class="subtitle">Girls Hostel Management</p>

    <ul class="menu">
        <li class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
            <a href="dashboard.php">📊 Dashboard</a>
        </li>

        <li class="<?= ($current_page == 'add_student.php') ? 'active' : '' ?>">
            <a href="add_student.php">➕ Add Student</a>
        </li>

        <li class="<?= ($current_page == 'view_students.php') ? 'active' : '' ?>">
            <a href="view_students.php">👩‍🎓 View Students</a>
        </li>

        <li class="<?= ($current_page == 'room_management.php') ? 'active' : '' ?>">
            <a href="room_management.php">🚪 Room Management</a>
        </li>

        <li class="<?= ($current_page == 'fee_management.php') ? 'active' : '' ?>">
            <a href="fee_management.php">💰 Fee Management</a>
        </li>
    </ul>

    <div class="logout">
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>