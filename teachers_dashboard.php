<?php
@include 'config.php';
session_start();

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher_user_id'])) {
    header('Location: login.php'); // Redirect to login if session is not set
    exit();
}

// Fetch the teacher's information
$teacher_id = $_SESSION['teacher_user_id'];
try {
    $query = "SELECT * FROM teachers WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$teacher_id]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching teacher details: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS for Teacher Panel -->
    <link rel="stylesheet" href="css/teacher.css">
</head>
<body>

<?php include 'users_header.php'; ?> <!-- Shared header for users -->

<section class="dashboard">
    <h1 class="title">Teacher Dashboard</h1>

    <div class="box-container">
        <!-- View Problem Banks Section -->
        <div class="box">
            <h3>0</h3> <!-- Dynamic count of problem banks can be added here -->
            <p>Problem Banks</p>
            <a href="view_problem_banks.php" class="btn">View Problem Banks</a>
        </div>

        <!-- Upload New Problem Bank Section -->
        <div class="box">
            <h3>Upload</h3>
            <p>Add New Problem Bank</p>
            <a href="upload_problem_bank.php" class="btn">Upload Problem Bank</a>
        </div>

        <!-- View Student Submissions Section -->
        <div class="box">
            <h3>0</h3> <!-- Dynamic count of submissions can be added here -->
            <p>Submissions</p>
            <a href="view_submissions.php" class="btn">View Submissions</a>
        </div>

        <!-- Provide Feedback to Students Section -->
        <div class="box">
            <h3>Feedback</h3>
            <p>Give Feedback to Students</p>
            <a href="provide_feedback.php" class="btn">Provide Feedback</a>
        </div>
    </div>
</section>

</body>
</html>
