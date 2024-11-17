<?php
@include 'config.php';
session_start();

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher_user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch submissions for the teacher's courses
try {
    $teacher_id = $_SESSION['teacher_user_id'];
    $query = "
        SELECT submissions.submission_id, submissions.student_id, submissions.problem_id, 
               students.first_name AS student_name, problems.title AS problem_title, submissions.grade, submissions.feedback
        FROM submissions
        INNER JOIN students ON submissions.student_id = students.student_id
        INNER JOIN problems ON submissions.problem_id = problems.problem_id
        INNER JOIN teachers ON problems.teacher_id = teachers.teacher_id
        WHERE teachers.user_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$teacher_id]);
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching submissions: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submissions</title>
    <link rel="stylesheet" href="css/users.css">
</head>
<body>

<?php include 'users_header.php'; ?>

<section class="dashboard">
    <h1 class="title">View Submissions</h1>

    <div class="box-container">
        <?php if (!empty($submissions)): ?>
            <?php foreach ($submissions as $submission): ?>
                <div class="box">
                    <h3>Problem: <?php echo htmlspecialchars($submission['problem_title']); ?></h3>
                    <p>Student: <?php echo htmlspecialchars($submission['student_name']); ?></p>
                    <p>Grade: <?php echo htmlspecialchars($submission['grade'] ?: 'Not Graded'); ?></p>
                    <p>Feedback: <?php echo htmlspecialchars($submission['feedback'] ?: 'No Feedback Yet'); ?></p>
                    <a href="view_submission_details.php?submission_id=<?php echo $submission['submission_id']; ?>" class="btn">View and Grade</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">No submissions found.</div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>