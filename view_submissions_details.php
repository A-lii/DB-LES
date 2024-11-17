<?php
@include 'config.php';
session_start();

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher_user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the submission ID
if (!isset($_GET['submission_id'])) {
    header('Location: view_submissions.php');
    exit();
}

$submission_id = $_GET['submission_id'];

// Fetch the specific submission details
try {
    $query = "
        SELECT submissions.submission_id, submissions.student_id, submissions.problem_id, 
               students.first_name AS student_name, problems.title AS problem_title, submissions.grade, submissions.feedback
        FROM submissions
        INNER JOIN students ON submissions.student_id = students.student_id
        INNER JOIN problems ON submissions.problem_id = problems.problem_id
        WHERE submissions.submission_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$submission_id]);
    $submission = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$submission) {
        die("Submission not found.");
    }
} catch (PDOException $e) {
    die("Error fetching submission details: " . $e->getMessage());
}

// Handle feedback and grade submission
if (isset($_POST['submit'])) {
    $feedback = filter_var($_POST['feedback'], FILTER_SANITIZE_STRING);
    $grade = filter_var($_POST['grade'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    try {
        $update_query = "UPDATE submissions SET feedback = ?, grade = ? WHERE submission_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->execute([$feedback, $grade, $submission_id]);
        $message[] = "Feedback and grade successfully updated.";
        header("Location: view_submissions.php");
        exit();
    } catch (PDOException $e) {
        die("Error updating feedback: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Details</title>
    <link rel="stylesheet" href="css/users.css">
</head>
<body>

<?php include 'users_header.php'; ?>

<section class="form-container">
    <h1 class="title">Submission Details</h1>

    <div class="box">
        <h3>Problem: <?php echo htmlspecialchars($submission['problem_title']); ?></h3>
        <p>Student: <?php echo htmlspecialchars($submission['student_name']); ?></p>
        <p>Submission:</p>
        <p>View the student's submission here on the platform.</p>
    </div>

    <form action="" method="POST">
        <textarea name="feedback" class="box" placeholder="Enter feedback..." required><?php echo htmlspecialchars($submission['feedback']); ?></textarea>
        <input type="number" name="grade" class="box" placeholder="Enter grade..." step="0.01" required value="<?php echo htmlspecialchars($submission['grade']); ?>">
        <input type="submit" name="submit" value="Submit Feedback and Grade" class="btn">
    </form>
</section>

</body>
</html>