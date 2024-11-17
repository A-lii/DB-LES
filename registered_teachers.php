<?php
@include 'config.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
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
?>

<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Teachers</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Include your CSS file -->
    <style>
        /* Center-align the table and make it responsive */
        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh; /* Adjust based on your header height */
        }

        table {
            width: 80%; /* Make it responsive */
            max-width: 1000px;
            border-collapse: collapse;
            text-align: left;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #f4f4f4;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            font-weight: bold;
            text-align: center;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        h1.title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>
<!-- Logout Button -->
<a href="logout.php" class="logout-btn">Logout</a>

<section>
    <h1 class="title">Registered Teachers</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Teacher ID</th>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Course Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($teachers)): ?>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($teacher['teacher_id']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['middle_name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['department']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['designation']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['course_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No teachers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>