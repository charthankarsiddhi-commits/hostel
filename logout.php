<?php
// logout.php - Logout and destroy session
// ============================================

require_once 'config.php';

// Destroy the session completely
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
