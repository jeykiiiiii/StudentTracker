<?php
session_start();
require_once '../functions/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : 0;

    // Check if the user is a student and get student_id
    $stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Student record not found.']);
        exit;
    }

    $student = $result->fetch_assoc();
    $student_id = $student['id'];

    // Check if already enrolled
    $checkStmt = $conn->prepare("SELECT id FROM enrollments WHERE student_id = ? AND class_id = ?");
    $checkStmt->bind_param("ii", $student_id, $class_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Already enrolled in this subject.']);
        exit;
    }

    // Insert enrollment record
    $enrollStmt = $conn->prepare("INSERT INTO enrollments (student_id, class_id) VALUES (?, ?)");
    $enrollStmt->bind_param("ii", $student_id, $class_id);

    if ($enrollStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Enrollment successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error during enrollment.']);
    }

    $enrollStmt->close();
    $conn->close();
}
