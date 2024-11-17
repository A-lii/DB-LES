<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php"); // Redirect to login if not logged in or not a student
    exit();
}

// Retrieve user information from the users table
$user_id = $_SESSION['user_id'];

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

// Fetch student-specific information from the students table
$student_sql = "SELECT department FROM students WHERE user_id = ?";
$student_stmt = $conn->prepare($student_sql);
$student_stmt->bind_param("i", $user_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();

if ($student_result->num_rows === 1) {
    $student = $student_result->fetch_assoc();
    $department = $student['department'];
} else {
    echo "Student information not found.";
    exit();
}

$student_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #2b3a4a;
            color: #ffffff;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        .profile-container {
            flex-grow: 1;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .profile-container h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>

    <!-- Sidebar with Home option -->
    <div class="sidebar">
        <h3>Navigation</h3>
        <a href="home.php">Home</a>
    </div>

    <!-- Student Profile Information -->
    <div class="profile-container">
        <h2>Student Profile</h2>
        <h3>Student Details</h3>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
        <p><strong>Middle Name:</strong> <?php echo htmlspecialchars($middle_name); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></p>
    </div>

</body>
</html>