<?php
// delete_student.php - Delete Student Record
// ============================================

require_once 'config.php';
checkLogin();

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id > 0) {
    // Delete the student (CASCADE will remove fees/complaints too)
    $delete = "DELETE FROM students WHERE student_id = $student_id";
    
    if (mysqli_query($conn, $delete)) {
        // Success - redirect with message
        header("Location: view_students.php?msg=deleted");
    } else {
        // Failure
        header("Location: view_students.php?msg=error");
    }
} else {
    // Invalid ID
    header("Location: view_students.php");
}
exit();
