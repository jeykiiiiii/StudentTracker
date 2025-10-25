<?php

function createAssessment($class_id, $title, $type, $total_score) {
    global $conn;
    $sql = "INSERT INTO assessments (class_id, title, type, total_score) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $class_id, $title, $type, $total_score);
    return $stmt->execute();
}


?>