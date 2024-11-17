<?php
@include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Encrypt the entered password to match the stored hash

    try {
        // Query to verify admin credentials
        $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username, $password]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Admin authenticated, set session variables
            $_SESSION['admin_id'] = $admin['admin_user_id']; // Store the admin's ID in the session
            $_SESSION['full_name'] = $admin['full_name']; // Optional: store admin's name for display
            header('Location: admin_page.php'); // Redirect to the admin dashboard
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


<!--middle one in notepad -->

<!-- </head>
<body>

   <section class="form-container">
      <form action="admin_login_action.php" method="POST">
         <h3>Admin Login</h3>
         <input type="text" name="username" class="box" placeholder="Enter your username" required>
         <input type="password" name="password" class="box" placeholder="Enter your password" required>
         <input type="submit" value="Login" class="btn">
      </form>
   </section>

</body>
</html> -->

<!--old one pasted from below-->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <!-- Font Awesome for Icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <section class="form-container">
        <form action="admin_login.php" method="POST"> <!-- Explicit action -->
            <h3>Admin Login</h3>
            <?php
            if (isset($message)) {
                echo '<div class="error">' . htmlspecialchars($message) . '</div>';
            }
            ?>
            <input type="text" name="username" class="box" placeholder="Enter your username" required>
            <input type="password" name="password" class="box" placeholder="Enter your password" required>
            <input type="submit" value="Login" class="btn" name="submit">
        </form>
    </section>
</body>
</html>


   <!-- Custom CSS -->
   <style>


      @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600&display=swap');
      :root {
         --green: #0056b3;
         --black: #333;
         --white: #fff;
         --light-bg: #f6f6f6;
         --border: .2rem solid var(--black);
         --box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
      }
      * {
         font-family: 'Rubik', sans-serif;
         margin: 0; padding: 0;
         box-sizing: border-box;
         outline: none; border: none;
         text-decoration: none; color: var(--black);
      }
      body {
         background-color: var(--light-bg);
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         margin: 0;
      }
      .form-container {
         background-color: var(--white);
         padding: 2rem;
         width: 40rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
         border: var(--border);
      }
      .form-container h3 {
         font-size: 3rem;
         color: var(--black);
         text-align: center;
         margin-bottom: 1.5rem;
      }
      .form-container .box {
         width: 100%;
         margin: 1rem 0;
         padding: 1.2rem;
         font-size: 1.8rem;
         border-radius: .5rem;
         border: var(--border);
         background-color: var(--light-bg);
      }
      .form-container .btn {
         width: 100%;
         padding: 1rem;
         font-size: 2rem;
         text-transform: capitalize;
         text-align: center;
         color: var(--white);
         background-color: var(--green);
         border-radius: .5rem;
         cursor: pointer;
         margin-top: 1.5rem;
      }
      .form-container .btn:hover {
         background-color: var(--black);
      }
   </style>

<!-- </head>
<body>

   <section class="form-container">
      <form action="admin_login_action.php" method="POST">
         <h3>Admin Login</h3>
         <input type="text" name="username" class="box" placeholder="Enter your username" required>
         <input type="password" name="password" class="box" placeholder="Enter your password" required>
         <input type="submit" value="Login" class="btn">
      </form>
   </section>

</body>
</html> -->