<?php
@include 'config.php';
session_start();

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher_user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the teacher_id for the logged-in teacher
$teacher_user_id = $_SESSION['teacher_user_id'];
try {
    $query = "SELECT teacher_id FROM teachers WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$teacher_user_id]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacher) {
        die("Error: No teacher found for the logged-in user.");
    }
    $teacher_id = $teacher['teacher_id'];
} catch (PDOException $e) {
    die("Error fetching teacher ID: " . $e->getMessage());
}

// Handle problem posting
if (isset($_POST['submit'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    try {
        // Fetch the last inserted problem_id
        $last_id_query = "SELECT problem_id FROM problems ORDER BY problem_id DESC LIMIT 1";
        $stmt = $conn->prepare($last_id_query);
        $stmt->execute();
        $last_id = $stmt->fetchColumn();

        // Generate the next problem_id
        if ($last_id) {
            $num = (int)substr($last_id, 2) + 1; // Extract numeric part and increment
            $new_id = 'PB' . str_pad($num, 4, '0', STR_PAD_LEFT); // Format as PB0001, PB0002, etc.
        } else {
            $new_id = 'PB0001'; // Start with PB0001 if no records exist
        }

        // Insert the new problem into the database
        $insert_query = "INSERT INTO problems (problem_id, teacher_id, title, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->execute([$new_id, $teacher_id, $title, $description]);

        $message[] = "Problem successfully posted with ID $new_id.";
    } catch (PDOException $e) {
        die("Error posting problem: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Problem Bank</title>
    <link rel="stylesheet" href="css/users.css">
    <link rel="stylesheet" href="css/teacher.css">
</head>
<body>

<?php include 'users_header.php'; ?>

<section class="form-container">
    <div class="form-content">
        <h1 class="title">Upload Problem Bank</h1> <!-- Title moved into the div for better alignment -->
        <form action="" method="POST">
            <input type="text" name="title" class="box" placeholder="Problem Title" required>
            <textarea name="description" class="box" placeholder="Write the problem description here..." required></textarea>
            <input type="submit" name="submit" value="Post Problem" class="btn">
        </form>
    </div>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo "<div class='message'><span>$msg</span></div>";
        }
    }
    ?>
</section>

</body>
</html>
