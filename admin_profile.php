<?php
@include 'config.php';
session_start();


// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch the admin's details
$admin_id = $_SESSION['admin_id'];

try {
    $query = "SELECT admin_user_id, full_name, username, email FROM admin WHERE admin_user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        die("Admin details not found.");
    }
} catch (PDOException $e) {
    die("Error fetching admin details: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php include 'admin_header.php'; ?>
<section>
    <h1 class="title">Admin Profile</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Admin ID</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo isset($admin['admin_user_id']) ? htmlspecialchars($admin['admin_user_id']) : 'N/A'; ?></td>
                    <td><?php echo isset($admin['full_name']) ? htmlspecialchars($admin['full_name']) : 'N/A'; ?></td>
                    <td><?php echo isset($admin['username']) ? htmlspecialchars($admin['username']) : 'N/A'; ?></td>
                    <td><?php echo isset($admin['email']) ? htmlspecialchars($admin['email']) : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>
