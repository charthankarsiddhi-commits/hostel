<?php
// room_management.php - Room Management Page
// ============================================

require_once 'config.php';
checkLogin();

$success = '';
$error   = '';

// ============================================
// Handle: Add New Room
// ============================================
if (isset($_POST['action']) && $_POST['action'] === 'add_room') {

    $room_number = trim(mysqli_real_escape_string($conn, $_POST['room_number']));
    $room_type   = trim(mysqli_real_escape_string($conn, $_POST['room_type']));
    $capacity    = (int)$_POST['capacity'];

    if (empty($room_number) || empty($room_type) || $capacity <= 0) {
        $error = "⚠️ Please fill in all room fields correctly.";
    } else {
        // Check if room number already exists
        $check = mysqli_query($conn, "SELECT room_id FROM rooms WHERE room_number = '$room_number'");
        if (mysqli_num_rows($check) > 0) {
            $error = "⚠️ Room number '$room_number' already exists.";
        } else {
            $insert = "INSERT INTO rooms (room_number, room_type, capacity, occupied) 
                       VALUES ('$room_number', '$room_type', $capacity, 0)";
            if (mysqli_query($conn, $insert)) {
                $success = "✅ Room $room_number added successfully!";
            } else {
                $error = "❌ Error: " . mysqli_error($conn);
            }
        }
    }
}

// ============================================
// Handle: Allocate Room to Student
// ============================================
if (isset($_POST['action']) && $_POST['action'] === 'allocate') {

    $student_id = (int)$_POST['student_id'];
    $room_id    = (int)$_POST['room_id'];
    $check_in   = trim(mysqli_real_escape_string($conn, $_POST['check_in']));

    if ($student_id <= 0 || $room_id <= 0 || empty($check_in)) {
        $error = "⚠️ Please select a student, room, and check-in date.";
    } else {
        // Check if student already has an allocation
        $already = mysqli_query($conn, "SELECT allocation_id FROM allocations WHERE student_id = $student_id AND check_out IS NULL");
        if (mysqli_num_rows($already) > 0) {
            $error = "⚠️ This student is already allocated to a room.";
        } else {
            // Check room availability
            $room_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rooms WHERE room_id = $room_id"));
            if ($room_row['occupied'] >= $room_row['capacity']) {
                $error = "⚠️ Room is already full!";
            } else {
                // Add allocation
                mysqli_query($conn, "INSERT INTO allocations (student_id, room_id, check_in) VALUES ($student_id, $room_id, '$check_in')");
                // Update room occupied count
                mysqli_query($conn, "UPDATE rooms SET occupied = occupied + 1 WHERE room_id = $room_id");
                $success = "✅ Room allocated successfully!";
            }
        }
    }
}

// ============================================
// Handle: Delete Room
// ============================================
if (isset($_GET['delete_room'])) {
    $room_id = (int)$_GET['delete_room'];
    mysqli_query($conn, "DELETE FROM rooms WHERE room_id = $room_id");
    $success = "✅ Room deleted successfully!";
}

// ============================================
// Handle: Deallocate (Check-out student)
// ============================================
if (isset($_GET['deallocate'])) {
    $alloc_id = (int)$_GET['deallocate'];
    // Get room_id from this allocation
    $alloc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT room_id FROM allocations WHERE allocation_id = $alloc_id"));
    if ($alloc) {
        // Set check-out date to today
        mysqli_query($conn, "UPDATE allocations SET check_out = CURDATE() WHERE allocation_id = $alloc_id");
        // Decrease room occupied count
        mysqli_query($conn, "UPDATE rooms SET occupied = GREATEST(occupied - 1, 0) WHERE room_id = " . $alloc['room_id']);
        $success = "✅ Student checked out successfully!";
    }
}

// ---- Fetch Data ----
$rooms    = mysqli_query($conn, "SELECT * FROM rooms ORDER BY room_number ASC");
$students = mysqli_query($conn, "SELECT s.* FROM students s LEFT JOIN allocations a ON s.student_id = a.student_id AND a.check_out IS NULL WHERE a.allocation_id IS NULL ORDER BY s.name");
$available_rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE occupied < capacity ORDER BY room_number ASC");

// Allocations list
$allocations = mysqli_query($conn,
    "SELECT a.*, s.name as student_name, r.room_number, r.room_type
     FROM allocations a
     JOIN students s ON a.student_id = s.student_id
     JOIN rooms r ON a.room_id = r.room_id
     WHERE a.check_out IS NULL
     ORDER BY a.check_in DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management - Hostel Management</title>
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
                <span class="page-title">🚪 Room Management</span>
            </div>
            <div class="admin-info">
                <div class="avatar">A</div>
                <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            </div>
        </div>

        <div class="content">

            <div class="page-header">
                <h1>Room Management</h1>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Forms Row -->
            <div class="grid-2">

                <!-- Add Room Form -->
                <div class="card">
                    <div class="card-header"><h2>➕ Add New Room</h2></div>
                    <div class="card-body">
                        <form method="POST" action="room_management.php">
                            <input type="hidden" name="action" value="add_room">

                            <div class="form-group">
                                <label>Room Number *</label>
                                <input type="text" name="room_number" placeholder="e.g. R101, B205" required>
                            </div>

                            <div class="form-group">
                                <label>Room Type *</label>
                                <select name="room_type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Single">Single (1 person)</option>
                                    <option value="Double">Double (2 persons)</option>
                                    <option value="Triple">Triple (3 persons)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Capacity *</label>
                                <input type="number" name="capacity" placeholder="Max number of students" min="1" max="10" required>
                            </div>

                            <button type="submit" class="btn btn-primary">➕ Add Room</button>
                        </form>
                    </div>
                </div>

                <!-- Allocate Room Form -->
                <div class="card">
                    <div class="card-header"><h2>🏠 Allocate Room to Student</h2></div>
                    <div class="card-body">
                        <form method="POST" action="room_management.php">
                            <input type="hidden" name="action" value="allocate">

                            <div class="form-group">
                                <label>Select Student *</label>
                                <select name="student_id" required>
                                    <option value="">-- Select Unallocated Student --</option>
                                    <?php
                                    // Reset pointer
                                    $students = mysqli_query($conn, "SELECT s.* FROM students s LEFT JOIN allocations a ON s.student_id = a.student_id AND a.check_out IS NULL WHERE a.allocation_id IS NULL ORDER BY s.name");
                                    while ($s = mysqli_fetch_assoc($students)): ?>
                                        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Select Available Room *</label>
                                <select name="room_id" required>
                                    <option value="">-- Select Room --</option>
                                    <?php
                                    $available_rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE occupied < capacity ORDER BY room_number");
                                    while ($r = mysqli_fetch_assoc($available_rooms)): ?>
                                        <option value="<?= $r['room_id'] ?>">
                                            <?= htmlspecialchars($r['room_number']) ?> 
                                            (<?= $r['room_type'] ?>, 
                                            <?= $r['capacity'] - $r['occupied'] ?> space left)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Check-in Date *</label>
                                <input type="date" name="check_in" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <button type="submit" class="btn btn-success">🏠 Allocate Room</button>
                        </form>
                    </div>
                </div>

            </div><!-- end grid-2 -->

            <!-- Room Cards / Availability Grid -->
            <div class="card">
                <div class="card-header"><h2>🚪 All Rooms - Availability</h2></div>
                <div class="card-body">
                    <?php
                    $rooms = mysqli_query($conn, "SELECT * FROM rooms ORDER BY room_number ASC");
                    if (mysqli_num_rows($rooms) > 0):
                    ?>
                    <div class="room-grid">
                        <?php while ($room = mysqli_fetch_assoc($rooms)): ?>
                        <?php
                            $is_full = ($room['occupied'] >= $room['capacity']);
                            $class   = $is_full ? 'full' : 'available';
                        ?>
                        <div class="room-card <?= $class ?>">
                            <div class="room-num"><?= htmlspecialchars($room['room_number']) ?></div>
                            <div class="room-type"><?= $room['room_type'] ?></div>
                            <div class="occupancy">
                                <?php if ($is_full): ?>
                                    <span class="badge badge-danger">Full 🔴</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Available 🟢</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:0.78rem; color:var(--text-light); margin-bottom:10px;">
                                <?= $room['occupied'] ?>/<?= $room['capacity'] ?> occupied
                            </div>
                            <a href="room_management.php?delete_room=<?= $room['room_id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete room <?= $room['room_number'] ?>?')">
                               🗑️ Delete
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                        <p class="no-data">No rooms added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Current Allocations Table -->
            <div class="card">
                <div class="card-header"><h2>📋 Current Room Allocations</h2></div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Room</th>
                                <th>Room Type</th>
                                <th>Check-in Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $allocations = mysqli_query($conn,
                            "SELECT a.*, s.name as student_name, r.room_number, r.room_type
                             FROM allocations a
                             JOIN students s ON a.student_id = s.student_id
                             JOIN rooms r ON a.room_id = r.room_id
                             WHERE a.check_out IS NULL
                             ORDER BY a.check_in DESC"
                        );
                        if (mysqli_num_rows($allocations) > 0):
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($allocations)):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><strong><?= htmlspecialchars($row['student_name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['room_number']) ?></td>
                            <td><?= htmlspecialchars($row['room_type']) ?></td>
                            <td><?= $row['check_in'] ?></td>
                            <td>
                                <a href="room_management.php?deallocate=<?= $row['allocation_id'] ?>"
                                   class="btn btn-warning btn-sm"
                                   onclick="return confirm('Check out this student?')">
                                   🚶 Check Out
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="6" class="no-data">No active allocations.</td></tr>
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
