<?php
session_start();
include('../functions/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $name = trim($_POST['name']);
    $id_number = trim($_POST['id_number']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Validate required fields
    if (empty($name) || empty($id_number) || empty($password) || empty($role)) {
        echo "<script>alert('All fields are required!'); window.location.href = '../public/index.php';</script>";
        exit();
    }

    // Validate email if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.location.href = '../public/index.php';</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if ID number already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE id_number = ?");
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('ID Number already registered!'); window.location.href = '../public/index.php';</script>";
        exit();
    }
    $stmt->close();

    // Insert user record
    $stmt = $conn->prepare("INSERT INTO users (name, id_number, password, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $id_number, $hashed_password, $email, $role);

    if ($stmt->execute()) {
        // Get the last inserted user_id
        $user_id = $conn->insert_id;

        // Insert a blank record in the appropriate table
        if ($role == 'student') {
            $stmt = $conn->prepare("INSERT INTO students (user_id, course, year_level, age, address, contact_number) VALUES (?, '', 0, 0, '', '')");
        } elseif ($role == 'instructor') {
            $stmt = $conn->prepare("INSERT INTO instructors (user_id, department, age, address, contact_number) VALUES (?, '', 0, '', '')");
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        echo "<script>alert('Registration successful! Please login.'); window.location.href = '../public/index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Something went wrong. Try again.'); window.location.href = '../public/index.php';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
