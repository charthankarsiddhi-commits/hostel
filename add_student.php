<?php
// add_student.php - Add New Student
// ============================================

require_once 'config.php';
checkLogin(); // Must be logged in

$success = '';
$error   = '';

// ---- Handle Form Submission ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and clean form data
    $name    = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $gender  = trim(mysqli_real_escape_string($conn, $_POST['gender']));
    $phone   = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $email   = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $address = trim(mysqli_real_escape_string($conn, $_POST['address']));

    // Validate required fields
    if (empty($name) || empty($phone) || empty($email) || empty($address)) {
        $error = "⚠️ Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "⚠️ Please enter a valid email address.";

    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "⚠️ Phone number must be exactly 10 digits.";

    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT student_id FROM students WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "⚠️ A student with this email already exists.";
        } else {
            // Insert student record
            $insert = "INSERT INTO students (name, gender, phone, email, address) 
                       VALUES ('$name', '$gender', '$phone', '$email', '$address')";

            if (mysqli_query($conn, $insert)) {
                $success = "✅ Student <strong>$name</strong> added successfully!";
                // Clear POST data after success
                $_POST = [];
            } else {
                $error = "❌ Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Hostel Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="flex gap-1">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <span></span><span></span><span></span>
                </button>
                <span class="page-title">➕ Add New Student</span>
            </div>
            <div class="admin-info">
                <div class="avatar">A</div>
                <span><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            </div>
        </div>

        <!-- Content -->
        <div class="content">

            <div class="page-header">
                <h1>Add New Student</h1>
                <a href="view_students.php" class="btn btn-info">👀 View All Students</a>
            </div>

            <!-- Success / Error Messages -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <!-- Add Student Form -->
            <div class="form-card">
                <h2>👩‍🎓 Student Information</h2>

                <form method="POST" action="add_student.php">

                    <div class="grid-2">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name"
                                placeholder="e.g. Priya Sharma"
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                required>
                        </div>

                        <!-- Gender -->
                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="Female" <?= (($_POST['gender'] ?? '') == 'Female') ? 'selected' : '' ?>>Female</option>
                                <option value="Other"  <?= (($_POST['gender'] ?? '') == 'Other')  ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone">Phone Number * (10 digits)</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone"
                                placeholder="e.g. 9876543210"
                                maxlength="10"
                                value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                placeholder="e.g. priya@example.com"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                required>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address">Home Address *</label>
                        <textarea 
                            id="address" 
                            name="address"
                            placeholder="Enter full home address..."
                            required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-1 mt-2">
                        <button type="submit" class="btn btn-primary">➕ Add Student</button>
                        <a href="view_students.php" class="btn btn-info">View All Students</a>
                        <button type="reset" class="btn btn-warning">🔄 Reset Form</button>
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
