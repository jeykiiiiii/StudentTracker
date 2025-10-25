<?php

function getInstructorClasses($instructor_id) {
    global $conn;
    $sql = "SELECT id, subject_name, schedule FROM classes WHERE instructor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $instructor_id);
    $stmt->execute();
    return $stmt->get_result();
}


?>