<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form submitted! Here is the data:<br>";
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    exit; // Stop further execution for debugging
}

include 'config.php';

if (isset($_POST['submit'])) {
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $middle_name = filter_var($_POST['middle_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $password = $_POST['pass']; // Store raw password temporarily
    $confirm_password = $_POST['cpass']; // Store confirmation password

    // Validate passwords
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    // Hash the password only after validation
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $role = $_POST['role'];

    try {
        // Generate the next user_id
        $last_id_sql = "SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
        $last_id_stmt = $conn->prepare($last_id_sql);
        $last_id_stmt->execute();
        $last_id = $last_id_stmt->fetchColumn();

        if ($last_id) {
            // Increment the numeric part of the last ID (e.g., U001 -> U002)
            $new_id = 'U' . str_pad((int)substr($last_id, 1) + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // If no users exist, start with U001
            $new_id = 'U001';
        }

        // Insert into users table
        $user_sql = "INSERT INTO users (user_id, first_name, middle_name, last_name, email, password, role) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->execute([$new_id, $first_name, $middle_name, $last_name, $email, $password, $role]);

        if ($user_stmt) {
            $user_id = $new_id; // Get the new user_id
            if ($role === 'teacher') {
                // Generate teacher ID
                $last_teacher_id_sql = "SELECT teacher_id FROM teachers ORDER BY teacher_id DESC LIMIT 1";
                $last_teacher_id_stmt = $conn->prepare($last_teacher_id_sql);
                $last_teacher_id_stmt->execute();
                $last_teacher_id = $last_teacher_id_stmt->fetchColumn();
            
                if ($last_teacher_id) {
                    $new_teacher_id = 'T' . str_pad((int)substr($last_teacher_id, 1) + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $new_teacher_id = 'T001';
                }
            
                // Insert into teachers table
                $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
                $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
                $designation = filter_var($_POST['designation'], FILTER_SANITIZE_STRING);
                $teacher_sql = "INSERT INTO teachers (teacher_id, user_id, first_name, middle_name, last_name, department, course_name, designation)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $teacher_stmt = $conn->prepare($teacher_sql);
                $teacher_stmt->execute([$new_teacher_id, $user_id, $first_name, $middle_name, $last_name, $department, $subject, $designation]);
            }            
             elseif ($role === 'student') {
                // Generate the next student_id
                $last_student_id_sql = "SELECT student_id FROM students ORDER BY student_id DESC LIMIT 1";
                $last_student_id_stmt = $conn->prepare($last_student_id_sql);
                $last_student_id_stmt->execute();
                $last_student_id = $last_student_id_stmt->fetchColumn();
            
                if ($last_student_id) {
                    $new_student_id = 'S' . str_pad((int)substr($last_student_id, 1) + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $new_student_id = 'S001';
                }
            
                $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
                $major = filter_var($_POST['major'], FILTER_SANITIZE_STRING);
                $cgpa = filter_var($_POST['cgpa'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $total_credits = filter_var($_POST['total_credits'], FILTER_SANITIZE_NUMBER_INT);
            
                // Insert into students table
                $student_sql = "INSERT INTO students (student_id, user_id, first_name, middle_name, last_name, department, major, total_credits, cgpa) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $student_stmt = $conn->prepare($student_sql);
                $student_stmt->execute([$new_student_id, $user_id, $first_name, $middle_name, $last_name, $department, $major, $total_credits, $cgpa]);
            }                    

            $message[] = ucfirst($role) . " registration successful.";
        } else {
            $message[] = "Failed to register. Please try again.";
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
    <title>Register</title>
    <link rel="stylesheet" href="css/components.css">
</head>

<body>

    <?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message"><span>' . $msg . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
    }
}
?>

    <section class="form-container">
        <form action="" enctype="multipart/form-data" method="POST">
            <h3>Register Now</h3>
            <!-- Role selection dropdown -->
            <select name="role" id="role" required>
                <option value="">Select Role</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <div id="common-fields">
                <input type="text" name="first_name" class="box" placeholder="First Name" required>
                <input type="text" name="middle_name" class="box" placeholder="Middle Name">
                <input type="text" name="last_name" class="box" placeholder="Last Name" required>
                <input type="email" name="email" class="box" placeholder="Enter Your Email" required>
                <input type="password" name="pass" class="box" placeholder="Enter Your Password" required>
                <input type="password" name="cpass" class="box" placeholder="Confirm Your Password" required>
            </div>

            <div id="student-fields" style="display: none;">
    <input type="text" name="department" class="box" placeholder="Department" required> 
    <input type="text" name="major" class="box" placeholder="Major">
    <input type="number" name="cgpa" class="box" placeholder="CGPA" step="0.01">
    <input type="number" name="total_credits" class="box" placeholder="Total Credits">
</div>

<div id="teacher-fields" style="display: none;">
    <input type="text" name="department" class="box" placeholder="Department">
    <input type="text" name="subject" class="box" placeholder="Subject"> <!-- Maps to course_name -->
    <input type="text" name="designation" class="box" placeholder="Designation">
</div>

            <input type="submit" value="Register Now" class="btn" name="submit">
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </section>

    <script>
    const roleSelect = document.getElementById('role');
    const teacherFields = document.getElementById('teacher-fields');
    const studentFields = document.getElementById('student-fields');

    // Show or hide fields based on the selected role
    roleSelect.addEventListener('change', function () {
    if (this.value === 'teacher') {
        teacherFields.style.display = 'block';
        studentFields.style.display = 'none';
    } else if (this.value === 'student') {
        studentFields.style.display = 'block';
        teacherFields.style.display = 'none';
    } else {
        teacherFields.style.display = 'none';
        studentFields.style.display = 'none';
    }
});
    </script>

</body>

</html>