<?php
// edit_student.php - Edit/Update Student Record
// ============================================

require_once 'config.php';
checkLogin();

$success = '';
$error   = '';

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate student ID
if ($student_id <= 0) {
    header("Location: view_students.php");
    exit();
}

// Fetch existing student data
$result = mysqli_query($conn, "SELECT * FROM students WHERE student_id = $student_id");
if (mysqli_num_rows($result) === 0) {
    header("Location: view_students.php");
    exit();
}
$student = mysqli_fetch_assoc($result);

// ---- Handle Update Form Submission ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $gender  = trim(mysqli_real_escape_string($conn, $_POST['gender']));
    $phone   = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $email   = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $address = trim(mysqli_real_escape_string($conn, $_POST['address']));

    // Validate
    if (empty($name) || empty($phone) || empty($email) || empty($address)) {
        $error = "⚠️ Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "⚠️ Please enter a valid email address.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "⚠️ Phone number must be exactly 10 digits.";
    } else {
        // Check email uniqueness (exclude current student)
        $check = mysqli_query($conn, "SELECT student_id FROM students WHERE email = '$email' AND student_id != $student_id");
        if (mysqli_num_rows($check) > 0) {
            $error = "⚠️ Another student already uses this email.";
        } else {
            // Update the record
            $update = "UPDATE students SET 
                        name    = '$name',
                        gender  = '$gender',
                        phone   = '$phone',
                        email   = '$email',
                        address = '$address'
                       WHERE student_id = $student_id";

            if (mysqli_query($conn, $update)) {
                // Redirect with success message
                header("Location: view_students.php?msg=updated");
                exit();
            } else {
                $error = "❌ Update failed: " . mysqli_error($conn);
            }
        }
    }

    // If there's an error, update the $student array with submitted values
    if (!empty($error)) {
        $student['name']    = $_POST['name'];
        $student['gender']  = $_POST['gender'];
        $student['phone']   = $_POST['phone'];
        $student['email']   = $_POST['email'];
        $student['address'] = $_POST['address'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Hostel Management</title>
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
                <span class="page-title">✏️ Edit Student</span>
            </div>
            <div class="admin-info">
                <div class="avatar">A</div>
                <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            </div>
        </div>

        <div class="content">

            <div class="page-header">
                <h1>Edit Student Details</h1>
                <a href="view_students.php" class="btn btn-info">← Back to List</a>
            </div>

            <!-- Alert Messages -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Edit Form -->
            <div class="form-card">
                <h2>✏️ Update: <?= htmlspecialchars($student['name']) ?></h2>

                <form method="POST" action="edit_student.php?id=<?= $student_id ?>">

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name"
                                value="<?= htmlspecialchars($student['name']) ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="Female" <?= ($student['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                <option value="Other"  <?= ($student['gender'] == 'Other')  ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number * (10 digits)</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone"
                                maxlength="10"
                                value="<?= htmlspecialchars($student['phone']) ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                value="<?= htmlspecialchars($student['email']) ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Home Address *</label>
                        <textarea id="address" name="address" required><?= htmlspecialchars($student['address']) ?></textarea>
                    </div>

                    <div class="flex gap-1 mt-2">
                        <button type="submit" class="btn btn-success">💾 Save Changes</button>
                        <a href="view_students.php" class="btn btn-warning">Cancel</a>
                    </div>

                </form>
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
