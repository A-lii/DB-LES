<?php
@include 'config.php';
session_start();

// Ensure the student is logged in
if (!isset($_SESSION['student_user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the logged-in student's ID
$student_id = $_SESSION['student_user_id'];

// Fetch the problems assigned by the teacher
try {
    $query = "
        SELECT problems.problem_id, problems.title, problems.description, teachers.first_name AS teacher_name
        FROM problems
        INNER JOIN teachers ON problems.teacher_id = teachers.teacher_id
        INNER JOIN students ON students.department = teachers.department
        WHERE students.student_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$student_id]);
    $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching problem bank: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/users.css">
</head>
<body>

<?php include 'users_header.php'; ?>

<section class="dashboard">
    <h1 class="title">Student Dashboard</h1>

    <div class="box-container">
        <!-- Problem Bank Section -->
        <div class="box">
            <h3>Problem Bank</h3>
            <p>View problems assigned by your teacher</p>
            <a href="view_problems.php" class="btn">View Problems</a>
        </div>
    </div>

</section>

</body>
</html>

