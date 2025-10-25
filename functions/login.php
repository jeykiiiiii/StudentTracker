<?php
session_start();
include('../functions/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_number = trim($_POST['id_number']);
    $password = trim($_POST['password']);

    // Check for empty fields
    if (empty($id_number) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href = '../public/index.php';</script>";
        exit();
    }

    // Prepare query to fetch user data
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE id_number = ?");
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password, $role);
    $stmt->fetch();

    // Verify password and check if user exists
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;

        // Handle role-based redirection and additional user info
        if ($role === "student") {
            // Get student-specific data
            $student_stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
            $student_stmt->bind_param("i", $id);
            $student_stmt->execute();
            $student_stmt->store_result();
            
            if ($student_stmt->num_rows > 0) {
                $student_stmt->bind_result($student_id);
                $student_stmt->fetch();
                $_SESSION['student_id'] = $student_id; // Store student_id in session
            }
            
            // Redirect to student homepage
            echo "<script>alert('Login successful! Welcome, $name.'); window.location.href = '../students/homepage.php';</script>";

        } elseif ($role === "instructor") {
            // Get instructor-specific data
            $instructor_stmt = $conn->prepare("SELECT id FROM instructors WHERE user_id = ?");
            $instructor_stmt->bind_param("i", $id);
            $instructor_stmt->execute();
            $instructor_stmt->store_result();
            
            if ($instructor_stmt->num_rows > 0) {
                $instructor_stmt->bind_result($instructor_id);
                $instructor_stmt->fetch();
                $_SESSION['instructor_id'] = $instructor_id; // Store instructor_id in session
            }
            
            // Redirect to instructor dashboard
            echo "<script>alert('Login successful! Welcome, $name.'); window.location.href = '../instructors/instructordashboard.php';</script>";

        } else {
            echo "<script>alert('Invalid role. Contact admin!'); window.location.href = '../public/index.php';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Invalid ID Number or Password!'); window.location.href = '../public/index.php';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
