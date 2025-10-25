<?php

include('../functions/db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = intval($_POST['student_id']);
    $assessment_id = intval($_POST['assessment_id']);
    $class_id = intval($_POST['class_id']);
    $score = intval($_POST['score']);

    // Check if the score already exists
    $checkQuery = "SELECT id FROM student_scores WHERE student_id = ? AND assessment_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $student_id, $assessment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing score
        $updateQuery = "UPDATE student_scores SET score = ?, recorded_at = NOW() WHERE student_id = ? AND assessment_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $score, $student_id, $assessment_id);
    } else {
        // Insert new score
        $insertQuery = "INSERT INTO student_scores (student_id, assessment_id, score, recorded_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iii", $student_id, $assessment_id, $score);
    }

    // Execute query and handle success or error
    if ($stmt->execute()) {
        echo "<script>
                alert('Score successfully updated!');
                window.location.href = '../instructors/manage_scores.php?student_id=$student_id&class_id=$class_id';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
                window.history.back();
              </script>";
    }
}
?>
