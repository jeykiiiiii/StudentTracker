<?php
session_start();
require_once '../functions/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'User not logged in']));
}

$user_id = $_SESSION['user_id'];

// Verify if the user is a student
$stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Student record not found']));
}

$student = $result->fetch_assoc();
$student_id = $student['id'];

// Fetch subjects, instructor, and schedule
$query = "
    SELECT c.id AS class_id, c.subject_name, c.schedule, u.name AS instructor_name
    FROM enrollments e
    JOIN classes c ON e.class_id = c.id
    JOIN instructors i ON c.instructor_id = i.id
    JOIN users u ON i.user_id = u.id
    WHERE e.student_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

echo json_encode($subjects);
?>
