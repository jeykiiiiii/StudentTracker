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

    // Get student ID from user_id
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

    // Verify if enrolled in the class
    $checkStmt = $conn->prepare("SELECT id FROM enrollments WHERE student_id = ? AND class_id = ?");
    $checkStmt->bind_param("ii", $student_id, $class_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Not enrolled in this subject.']);
        exit;
    }

    // Delete enrollment
    $deleteStmt = $conn->prepare("DELETE FROM enrollments WHERE student_id = ? AND class_id = ?");
    $deleteStmt->bind_param("ii", $student_id, $class_id);

    if ($deleteStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Subject dropped successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error dropping subject.']);
    }

    $deleteStmt->close();
    $conn->close();
}
