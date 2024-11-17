<?php
@include 'config.php';

session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];

    try {
        // Fetch user details
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            // Check user role
            if ($user['role'] === 'admin') {
                $_SESSION['admin_user_id'] = $user['user_id'];
                header('location:admin_page.php');
                exit();
            } elseif ($user['role'] === 'teacher') {
                $_SESSION['teacher_user_id'] = $user['user_id'];
                header('location:teachers_dashboard.php');
                exit();
            } elseif ($user['role'] === 'student') {
                $_SESSION['student_user_id'] = $user['user_id'];
                header('location:students_dashboard.php');
                exit();
            } else {
                $message[] = 'Invalid role specified.';
            }
        } else {
            $message[] = 'Incorrect email or password!';
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/components.css">
</head>

<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<section class="form-container">
    <form action="" method="POST">
        <h3>Login Now</h3>
        <input type="email" name="email" class="box" placeholder="Enter your email" required>
        <input type="password" name="pass" class="box" placeholder="Enter your password" required>
        <input type="submit" value="Login Now" class="btn" name="submit">
        <p>Don't have an account? <a href="register.php">Register Now</a></p>
    </form>
</section>

</body>

</html>
