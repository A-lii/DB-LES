<?php
@include 'config.php'; // Include your database connection
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to login if session is not set
    exit();
}

// Fetch all teachers from the database
try {
    $query = "SELECT * FROM teachers";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching teachers: " . $e->getMessage());
}

// Fetch the count of registered students
try {
    $query_students = "SELECT COUNT(*) AS student_count FROM students";
    $stmt_students = $conn->prepare($query_students);
    $stmt_students->execute();
    $student_count = $stmt_students->fetch(PDO::FETCH_ASSOC)['student_count'];
} catch (PDOException $e) {
    die("Error fetching students: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>

<?php include 'admin_header.php'; ?>


<section class="dashboard">
    <h1 class="title">DB-LES Dashboard</h1>

    <div class="box-container">
        <!-- Registered Teachers Section -->
        <div class="box">
            <h3><?php echo count($teachers); ?></h3>
            <p>Registered Teachers</p>
            <a href="registered_teachers.php" class="btn">View Teachers</a>
        </div>

        <!-- Registered Students Section -->
        <div class="box">
            <h3><?php echo $student_count; ?></h3>
            <p>Registered Students</p>
            <a href="registered_students.php" class="btn">View Students</a>
        </div>

        <div class="box">
            <h3>0</h3>
            <p>Add Exams</p>
            <a href="add_exams.php" class="btn">Add Exams</a>
<style>
    .btn {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        text-decoration: none;
        border-radius: 5px;
    }

</style>


        </div>

        <div class="box">
            <h3>0</h3>
            <p>Feedback</p>
            <a href="#" class="btn">Manage Feedbacks</a>
        </div>
    </div>
</section>
</body>
</html>

