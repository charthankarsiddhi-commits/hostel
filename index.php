<?php
// index.php - Admin Login Page
// ============================================

// Include database config (also starts session)
require_once 'config.php';

// If admin is already logged in, go to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = ''; // Variable to hold error messages

// ---- Handle Login Form Submission ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and sanitize form inputs
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "⚠️ Please enter both username and password.";
    } else {
        // Hash password with MD5 (same as stored in DB)
        $hashed_password = MD5($password);

        // Query the admin table
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$hashed_password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            // Login successful!
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username']  = $admin['username'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid username or password. Try admin / admin123";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hostel Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Login Page Wrapper -->
<div class="login-wrapper">
    <div class="login-box">

        <!-- Logo -->
        <div class="logo">🏠 Hostel<span>MS</span></div>
        <div class="subtitle">College Girls Hostel Management System</div>

        <h2>Admin Login</h2>

        <!-- Show error message if login failed -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="index.php">

            <div class="form-group">
                <label for="username">👤 Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Enter username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary btn-full mt-2">
                Login →
            </button>

        </form>

        <!-- Hint for default credentials -->
        <p class="text-muted mt-2" style="font-size:0.78rem;">
            Default: <strong>admin</strong> / <strong>admin123</strong>
        </p>

    </div>
</div>

</body>
</html>
