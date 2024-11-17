<?php
@include 'config.php';
session_start();

// Ensure the student is logged in
if (!isset($_SESSION['student_user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $student_id = $_SESSION['student_user_id']; // Get the logged-in student's ID
    $problem_id = filter_var($_POST['problem_id'], FILTER_SANITIZE_STRING);
    $submission_content = filter_var($_POST['submission_content'], FILTER_SANITIZE_STRING); // Text of the submission

    try {
        // Fetch the last inserted submission_id
        $last_id_query = "SELECT submission_id FROM submissions ORDER BY submission_id DESC LIMIT 1";
        $stmt = $conn->prepare($last_id_query);
        $stmt->execute();
        $last_id = $stmt->fetchColumn();

        // Generate the next submission_id
        if ($last_id) {
            $num = (int)substr($last_id, 2) + 1; // Extract numeric part and increment
            $new_id = 'SB' . str_pad($num, 3, '0', STR_PAD_LEFT); // Format as SB001, SB002, etc.
        } else {
            $new_id = 'SB001'; // Start with SB001 if no records exist
        }

        // Insert the submission into the database
        $insert_query = "INSERT INTO submissions (submission_id, student_id, problem_id, grade, feedback) 
                         VALUES (?, ?, ?, NULL, NULL)";
        $stmt = $conn->prepare($insert_query);
        $stmt->execute([$new_id, $student_id, $problem_id]);

        $message[] = "Submission successfully added with ID $new_id.";

        // Optional: Display or store the submission content elsewhere if needed
        // For example, you can save it in a separate table or directory if required.

    } catch (PDOException $e) {
        die("Error creating submission: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Answer</title>
    <link rel="stylesheet" href="css/users.css">
</head>
<body>

<?php include 'users_header.php'; ?>

<section class="form-container">
    <form action="" method="POST">
        <h3>Submit Your Answer</h3>
        <input type="text" name="problem_id" class="box" placeholder="Problem ID" required>
        <textarea name="submission_content" class="box" placeholder="Write your answer here..." required></textarea>
        <input type="submit" name="submit" value="Submit Answer" class="btn">
    </form>
</section>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo "<div class='message'><span>$msg</span></div>";
    }
}
?>

</body>
</html>
