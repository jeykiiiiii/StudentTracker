<?php 

function getStudentScores($student_id) {
    global $conn;
    $sql = "SELECT a.title, a.type, s.score, a.total_score
            FROM student_scores s
            JOIN assessments a ON s.assessment_id = a.id
            WHERE s.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    return $stmt->get_result();
}


?>