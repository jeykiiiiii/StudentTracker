<?php
require 'db_connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's role first
    $roleQuery = "SELECT role FROM users WHERE id = ?";
    $stmt = $conn->prepare($roleQuery);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $roleRow = $result->fetch_assoc();
    $role = $roleRow['role'];

    // Get form data
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $address = $_POST['address'] ?? '';

    // Update user table
    $updateUser = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($updateUser);
    $stmt->bind_param('ssi', $fullName, $email, $user_id);
    
    if ($stmt->execute()) {
        // Update student or instructor table based on role
        if ($role === 'student') {
            $yearLevel = $_POST['year_level'] ?? '';
            $course = $_POST['course'] ?? '';

            $updateStudent = "UPDATE students SET year_level = ?, age = ?, course = ?, address = ?, contact_number = ? WHERE user_id = ?";
            $stmt = $conn->prepare($updateStudent);
            $stmt->bind_param('iisssi', $yearLevel, $age, $course, $address, $mobile, $user_id);
            $stmt->execute();
        } else {
            $department = $_POST['department'] ?? '';

            $updateInstructor = "UPDATE instructors SET department = ?, age = ?, address = ?, contact_number = ? WHERE user_id = ?";
            $stmt = $conn->prepare($updateInstructor);
            $stmt->bind_param('sissi', $department, $age, $address, $mobile, $user_id);
            $stmt->execute();
        }

        // Redirect with success message
        header("Location: ../students/profile.php?updated=1");
        exit;
    } else {
        // Redirect with error message
        header("Location: ../students/profile.php?error=1");
        exit;
    }
} else {
    // If not POST request, redirect back
    header("Location: ../students/profile.php");
    exit;
}
?>