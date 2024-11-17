<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php"); // Redirect to login if not logged in or not a teacher
    exit();
}

// Retrieve user information using the session user_id
$user_id = $_SESSION['user_id'];

// Fetch basic user information from the users table
$user_sql = "SELECT first_name, middle_name, last_name, email FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 1) {
    $user = $user_result->fetch_assoc();
    $first_name = $user['first_name'];
    $middle_name = $user['middle_name'];
    $last_name = $user['last_name'];
    $email = $user['email'];
} else {
    echo "User not found.";
    exit();
}

$user_stmt->close();

// Fetch teacher-specific information from the teachers table
$teacher_sql = "SELECT department, subject, designation FROM teachers WHERE user_id = ?";
$teacher_stmt = $conn->prepare($teacher_sql);
$teacher_stmt->bind_param("i", $user_id);
$teacher_stmt->execute();
$teacher_result = $teacher_stmt->get_result();

if ($teacher_result->num_rows === 1) {
    $teacher = $teacher_result->fetch_assoc();
    $department = $teacher['department'];
    $subject = $teacher['subject'];
    $designation = $teacher['designation'];
} else {
    echo "Teacher profile information not found.";
    exit();
}

$teacher_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="profile-container">
        <h2><?php echo htmlspecialchars("$first_name $middle_name $last_name's Profile"); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        
        <h3>Teacher Information</h3>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></p>
        <p><strong>Subject:</strong> <?php echo htmlspecialchars($subject); ?></p>
        <p><strong>Designation:</strong> <?php echo htmlspecialchars($designation); ?></p>
        
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
