<?php

function assignScore($student_id, $assessment_id, $score) {
    global $conn;
    $sql = "INSERT INTO student_scores (student_id, assessment_id, score) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $student_id, $assessment_id, $score);
    return $stmt->execute();
}


?>