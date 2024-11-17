<?php

if(isset($message)){ //a safeguard to ensure the script doesnt attempt to process an undefined variable
    foreach($message as $message){ //current msg temporarily stored, overwriting its value; its a loop/assumed array 
                                  //fas fa times Font Awesome^ here used for close button 
        echo '
        <div class="message">
        <span>' .$message. '</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i> 
    </div> 
    '; 
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS for User Panel -->
    <link rel="stylesheet" href="css/users.css">
</head>
<body>

<header class="header">
    <div class="flex">
        <!-- Logo with dynamic link and title based on role -->
        <?php
            if (isset($_SESSION['teacher_user_id'])) {
                // Display Teacher Panel
                echo '<a href="teachers_dashboard.php" class="logo">Teacher<span>Panel</span></a>';
            } elseif (isset($_SESSION['student_user_id'])) {
                // Display Student Panel
                echo '<a href="students_dashboard.php" class="logo">Student<span>Panel</span></a>';
            }
        ?>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <a href="teachers_dashboard.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact Us</a>
        </nav>

        <!-- Icons -->
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div> <!-- Menu Button -->
            <a id="user-btn" href="profile.php" class="fas fa-user"></a> <!-- Profile Icon -->
            <a href="logout.php" class="btn logout-btn">Logout</a> <!-- Logout Button -->
        </div>

        <!-- Profile Section (Displays User's Name) -->
        <div class="profile">
            <img src="uploaded_img/default.png" alt="Default Profile Picture"> <!-- Placeholder image -->
            <p>
                <?php
                // Display logged-in user's full name
                @include 'config.php';
                if (isset($_SESSION['teacher_user_id'])) {
                    // For Teacher
                    $user_id = $_SESSION['teacher_user_id'];
                    $query = "SELECT first_name FROM teachers WHERE user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo htmlspecialchars($user['first_name']); // Display teacher's name
                } elseif (isset($_SESSION['student_user_id'])) {
                    // For Student
                    $user_id = $_SESSION['student_user_id'];
                    $query = "SELECT first_name FROM students WHERE user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo htmlspecialchars($user['first_name']); // Display student's name
                } else {
                    echo "User";
                }
                ?>
            </p>
            <a href="update_profile.php" class="btn">Update Profile</a>
        </div>
    </div>
</header>