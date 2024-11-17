<?php
@include 'config.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_name = $_POST['exam_name'];
    $access_level = $_POST['access_level'];

    try {
        $query = "INSERT INTO exams (exam_name, access_level) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$exam_name, $access_level]);
        $success_message = "Exam added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding exam: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exams</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section>
    <h1 class="title">Add Exams</h1>

    <div class="form-container">
        <form method="POST" action="">
            <label for="exam_name">Exam Name:</label>
            <input type="text" id="exam_name" name="exam_name" placeholder="Enter Exam Name (e.g., Quiz, Mid, Final)" required>

            <label for="access_level">Access Level:</label>
            <select id="access_level" name="access_level" required>
                <option value="quiz_mid">Quiz and Mid</option>
                <option value="final">Final</option>
            </select>

            <button type="submit" class="btn">Add Exam</button>
        </form>

        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</section>

<h2 class="title">Existing Exams</h2>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Exam ID</th>
                <th>Exam Name</th>
                <th>Access Level</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $query = "SELECT * FROM exams";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($exams)) {
                    foreach ($exams as $exam) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($exam['exam_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($exam['exam_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($exam['access_level']) . "</td>";
                        echo "<td>" . htmlspecialchars($exam['created_at']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No exams added yet.</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='4'>Error fetching exams: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


</body>
</html>
