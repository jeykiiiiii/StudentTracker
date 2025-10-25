<?php
require 'db_connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Step 1: Get the role
$roleQuery = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($roleQuery);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$roleRow = $result->fetch_assoc();

if (!$roleRow) {
    die("User not found.");
}

$role = $roleRow['role'];

// Step 2: Fetch user data
if ($role === 'student') {
    $query = "SELECT u.name, u.email, u.id_number, u.role, u.image,
                     s.course, s.year_level, s.age, s.address, s.contact_number
              FROM users u
              LEFT JOIN students s ON u.id = s.user_id
              WHERE u.id = ?";
} else {
    $query = "SELECT u.name, u.email, u.id_number, u.role, u.image,
                     i.department, i.age, i.address, i.contact_number
              FROM users u
              LEFT JOIN instructors i ON u.id = i.user_id
              WHERE u.id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User data not found.");
}

// Step 3: Get subjects for students
$subjects = [];
if ($role === 'student') {
    $subjectQuery = "SELECT c.subject_name 
                     FROM enrollments e 
                     JOIN classes c ON e.class_id = c.id 
                     JOIN students s ON e.student_id = s.id 
                     WHERE s.user_id = ?";
    $stmt = $conn->prepare($subjectQuery);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $subjectResult = $stmt->get_result();
    while ($row = $subjectResult->fetch_assoc()) {
        $subjects[] = $row['subject_name'];
    }
}

// Step 4: Get subjects managed by instructors
$managedSubjects = [];
if ($role === 'instructor') {
    $subjectQuery = "SELECT c.subject_name 
                     FROM classes c 
                     JOIN instructors i ON c.instructor_id = i.id 
                     WHERE i.user_id = ?";
    $stmt = $conn->prepare($subjectQuery);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $subjectResult = $stmt->get_result();
    while ($row = $subjectResult->fetch_assoc()) {
        $managedSubjects[] = $row['subject_name'];
    }
}

// Step 5: Handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    // Update user table
    $updateUser = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($updateUser);
    $stmt->bind_param('ssi', $fullName, $email, $user_id);
    $stmt->execute();

    if ($role === 'student') {
        $yearLevel = $_POST['year_level'];
        $course = $_POST['course'];

        $updateStudent = "UPDATE students SET year_level = ?, age = ?, course = ?, address = ?, contact_number = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateStudent);
        $stmt->bind_param('iisssi', $yearLevel, $age, $course, $address, $mobile, $user_id);
        $stmt->execute();
    } else {
        $department = $_POST['department'];

        $updateInstructor = "UPDATE instructors SET department = ?, age = ?, address = ?, contact_number = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateInstructor);
        $stmt->bind_param('sissi', $department, $age, $address, $mobile, $user_id);
        $stmt->execute();
    }

    // Redirect with success message using query parameter
    header("Location: ../students/profile.php?updated=1");
    exit;
}

// After all the logic above, you can include the subjects in the profile display:
?>


