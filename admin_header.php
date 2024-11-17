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
    <meta http-equiv="X-VA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>

    <!--font awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!--custom css file link-->
    <link rel="stylesheet" href="css/admin.css">

    <script src="js/script.js"></script>


</head>
<body>


<header class="header">
    <div class="flex">
        <!-- Logo -->
        <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <a href="admin_page.php">Home</a>
            <a href="about.php">About</a>
            <a href="contacts.php">Contact Us</a>
        </nav>

        <!-- Icons -->
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div> <!-- Menu Button -->
            <a id="user-btn" href="admin_profile.php" class="fas fa-user"></a>  <!-- User Button -->
            <a href="admin_login.php" class="btn logout-btn">Logout</a>

        </div>

        <!-- Profile Section 
        <div class="profile">
            <img src="uploaded_img/default.png" alt="Default Profile Picture"> 
            <p>Admin Name</p> 
            <a href="admin_update_profile.php" class="btn">Update Profile</a>
            <a href="logout.php" class="delete-btn">Logout</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>
            </div>
        </div>
    </div> -->

    <!-- Profile Section -->
<div class="profile">
    <img src="uploaded_img/default.png" alt="Default Profile Picture"> <!-- Placeholder image -->
    <p>
        <?php
        // Display logged-in admin's full name
        @include 'config.php';
        session_start();
        if (isset($_SESSION['admin_id'])) {
            $admin_id = $_SESSION['admin_id'];
            $query = "SELECT full_name FROM admin WHERE admin_user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$admin_id]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($admin['full_name']); // Display admin's full name
        } else {
            echo "Admin Name";
        }
        ?>
    </p>
    <a href="admin_update_profile.php" class="btn">Update Profile</a>
    <div class="flex-btn">
        <a href="login.php" class="option-btn">Login</a>
        <a href="register.php" class="option-btn">Register</a>
    </div>
</div>
</header>
