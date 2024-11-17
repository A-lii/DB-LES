<?php
@include 'config.php';
session_start();

// Ensure the student is logged in
if (!isset($_SESSION['student_user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the logged-in student's ID and department
$student_id = $_SESSION['student_user_id'];

try {
    // Fetch the department of the logged-in student
    $department_query = "SELECT department FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($department_query);
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Error: Student not found.");
    }
    $student_department = $student['department'];

    // Fetch problems assigned by teachers in the same department
    $query = "
        SELECT problems.problem_id, problems.title, problems.description, teachers.first_name AS teacher_name
        FROM problems
        INNER JOIN teachers ON problems.teacher_id = teachers.teacher_id
        WHERE teachers.department = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$student_department]);
    $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching problems: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Problems</title>
    <link rel="stylesheet" href="css/users.css">
</head>
<body>

<?php include 'users_header.php'; ?>

<section class="dashboard">
    <h1 class="title">Assigned Problems</h1>

    <div class="box-container">
        <?php if (!empty($problems)): ?>
            <?php foreach ($problems as $problem): ?>
                <div class="box">
                    <h3><?php echo htmlspecialchars($problem['title']); ?></h3>
                    <p><?php echo htmlspecialchars($problem['description']); ?></p>
                    <p><strong>Assigned By:</strong> <?php echo htmlspecialchars($problem['teacher_name']); ?></p>
                    <a href="student_submission.php?problem_id=<?php echo htmlspecialchars($problem['problem_id']); ?>" class="btn">Submit Answer</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No problems assigned yet.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
