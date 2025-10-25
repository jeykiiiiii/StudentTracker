<?php

include('db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = intval($_POST['class_id']);
    $title = trim($_POST['title']);
    $type = $_POST['type'];
    $total_score = intval($_POST['total_score']);

    // Validate input
    if (empty($title) || !in_array($type, ['quiz', 'activity', 'exam']) || $total_score <= 0) {
        header("Location: ../instructors/view_studentlist.php?class_id=$class_id&error=InvalidInput");
        exit();
    }

    // Insert assessment
    $query = "INSERT INTO assessments (class_id, title, type, total_score) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $class_id, $title, $type, $total_score);

    if ($stmt->execute()) {
        header("Location: ../instructors/manage_subject.php?class_id=$class_id&success=AssessmentAdded");
        exit();
    } else {
        header("Location: ../instructors/manage_subject.php?class_id=$class_id&error=DatabaseError");
        exit();
    }
}
?>
            <?php
        if (isset($_GET['success'])) {
            if ($_GET['success'] == 'AssessmentAdded') {
                echo "<script>alert('Assessment successfully added!');</script>";
            }
        } elseif (isset($_GET['error'])) {
            if ($_GET['error'] == 'InvalidInput') {
                echo "<script>alert('Invalid input data. Please check your entries.');</script>";
            } elseif ($_GET['error'] == 'DatabaseError') {
                echo "<script>alert('Error adding assessment to the database. Please try again later.');</script>";
            }
        }
        ?>