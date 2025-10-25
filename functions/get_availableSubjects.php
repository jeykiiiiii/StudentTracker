<?php
session_start();
require_once '../functions/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'User not logged in']));
}

$user_id = $_SESSION['user_id'];

// Find the student ID
$stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Student record not found']));
}

$student = $result->fetch_assoc();
$student_id = $student['id'];

// Get subjects NOT enrolled by the student
$query = "
    SELECT c.id AS class_id, c.subject_name, u.name AS instructor_name, c.schedule
    FROM classes c
    JOIN instructors i ON c.instructor_id = i.id
    JOIN users u ON i.user_id = u.id
    WHERE c.id NOT IN (
        SELECT e.class_id 
        FROM enrollments e 
        WHERE e.student_id = ?
    )
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$availableSubjects = [];
while ($row = $result->fetch_assoc()) {
    $availableSubjects[] = $row;
}

// Return available subjects as JSON
echo json_encode($availableSubjects);
